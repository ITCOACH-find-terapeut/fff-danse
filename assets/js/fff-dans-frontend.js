// assets/js/fff-danse-frontend.js
(function () {
    'use strict';

    // NY: gør både fulde YouTube-links og rene ID'er brugbare
    function toVideoId(value) {
        if (!value) return '';

        // Allerede et "rent" ID?
        if (/^[A-Za-z0-9_-]{11}$/.test(value)) {
            return value;
        }

        try {
            var url = new URL(value);

            // www.youtube.com/watch?v=ID
            var v = url.searchParams.get('v');
            if (v) return v;

            // youtu.be/ID
            if (url.hostname.indexOf('youtu.be') !== -1) {
                return url.pathname.replace(/^\//, '');
            }
        } catch (e) {
            // ikke en URL – brug bare value som fallback
        }

        return value;
    }

    function createIcon(angle) {
        // Simple line-SVG for fugl / normal / frø-perspektiv
        var svgNS = 'http://www.w3.org/2000/svg';
        var svg = document.createElementNS(svgNS, 'svg');
        svg.setAttribute('viewBox', '0 0 32 32');
        svg.setAttribute('aria-hidden', 'true');

        var line = document.createElementNS(svgNS, 'line');
        line.setAttribute('stroke', 'currentColor');
        line.setAttribute('stroke-width', '2');
        line.setAttribute('stroke-linecap', 'round');

        if (angle === 1) {
            // fugle-perspektiv – linje øverst, skrå ned
            line.setAttribute('x1', '4');  line.setAttribute('y1', '8');
            line.setAttribute('x2', '28'); line.setAttribute('y2', '16');
        } else if (angle === 2) {
            // normal – vandret i midten
            line.setAttribute('x1', '4');  line.setAttribute('y1', '16');
            line.setAttribute('x2', '28'); line.setAttribute('y2', '16');
        } else {
            // frø-perspektiv – linje nederst, skrå op
            line.setAttribute('x1', '4');  line.setAttribute('y1', '24');
            line.setAttribute('x2', '28'); line.setAttribute('y2', '16');
        }

        svg.appendChild(line);
        return svg;
    }

    function initPlayer(container) {
        var dataAttr = container.getAttribute('data-fff-danse-videos');
        if (!dataAttr) return;

        var videos;
        try {
            videos = JSON.parse(dataAttr);
        } catch (e) {
            return;
        }

        var iframe = container.querySelector('iframe');
        if (!iframe) return;

        var modeButtons = container.querySelectorAll('.fff-danse-mode-btn');
        var angleNav = container.querySelector('.fff-danse-angle-nav');

        var state = {
            mode: 'intro',
            angle: 1
        };

        function getCurrentVideoId() {
            var mode = state.mode;
            if (mode === 'intro') {
                return videos.intro || '';
            }
            var list = videos[mode] || [];
            var index = state.angle - 1;
            return list[index] || '';
        }

        // RETTET: brug toVideoId() før embed
        function updateIframe() {
            var raw = getCurrentVideoId();
            var id = toVideoId(raw);

            if (!id) {
                iframe.removeAttribute('src');
                container.classList.add('fff-danse-no-video');
                return;
            }
            container.classList.remove('fff-danse-no-video');

            var wrapper = container.querySelector('.fff-danse-video-inner');
            if (wrapper) {
                wrapper.classList.remove('is-visible');
                setTimeout(function () {
                    iframe.setAttribute(
                        'src',
                        'https://www.youtube.com/embed/' + encodeURIComponent(id)
                    );
                    wrapper.classList.add('is-visible');
                }, 150);
            } else {
                iframe.setAttribute(
                    'src',
                    'https://www.youtube.com/embed/' + encodeURIComponent(id)
                );
            }
        }

        function renderAngles() {
            angleNav.innerHTML = '';

            if (state.mode === 'intro') {
                angleNav.classList.add('is-empty');
                return;
            }

            var list = videos[state.mode] || [];
            if (!list.length) {
                angleNav.classList.add('is-empty');
                return;
            }

            angleNav.classList.remove('is-empty');

            list.forEach(function (id, index) {
                if (!id) return;

                var angle = index + 1;
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'fff-danse-angle-btn';
                btn.dataset.angle = String(angle);

                if (state.angle === angle) {
                    btn.classList.add('is-active');
                }

                btn.appendChild(createIcon(angle));

                btn.addEventListener('click', function () {
                    if (state.angle === angle) return;
                    state.angle = angle;
                    // opdater active-class
                    var all = angleNav.querySelectorAll('.fff-danse-angle-btn');
                    all.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');
                    updateIframe();
                });

                angleNav.appendChild(btn);
            });
        }

        // Klik på Intro/Se/Lær/Dans
        modeButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var mode = btn.dataset.mode;
                if (!mode || state.mode === mode) return;

                state.mode = mode;

                modeButtons.forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });

                // Ved skift til intro: nulstil vinkel til 1
                if (mode === 'intro') {
                    state.angle = 1;
                } else {
                    // find første tilgængelige vinkel
                    var list = videos[mode] || [];
                    state.angle = list.length ? 1 : 1;
                }

                renderAngles();
                updateIframe();
            });
        });

        // Init: hvis intro mangler, vælg første mode med data
        if (!videos.intro) {
            ['se', 'lær', 'dans'].some(function (m) {
                if (videos[m] && videos[m].length) {
                    state.mode = m;
                    // set active knap
                    modeButtons.forEach(function (b) {
                        b.classList.toggle('is-active', b.dataset.mode === m);
                    });
                    return true;
                }
                return false;
            });
        }

        renderAngles();
        updateIframe();
    }

    document.addEventListener('DOMContentLoaded', function () {
        var containers = document.querySelectorAll('.fff-danse-player');
        containers.forEach(initPlayer);
    });
})();