import Alpine from "alpinejs";
import Player from "@vimeo/player";

// Alpine v3 uses proxies for reactive data. In order for the Vimeo Player editor to operate properly with Alpine,
// we need to move it outside of Alpines data model.

// Vimeo Player on alpine init
document.addEventListener("alpine:init", () => {
    Alpine.data("vimeoPlayer", (options) => {
        let player;

        return {
            player() {
                return player;
            },
            async togglePlayback(state) {
                if (player) {
                    let isPaused = await player.getPaused();
                    state ??= isPaused;

                    if (state && isPaused) {
                        await player.ready();
                        await player.play();
                    } else if (!state && !isPaused) {
                        await player.ready();
                        await player.pause();
                        await player.setCurrentTime(0);
                    }
                }
            },
            init() {
                let dispatch = this.$dispatch;
                player = new Player(this.$root, options);
                let vimeoEvents = [
                    // Events for playback controls
                    // https://developer.vimeo.com/player/sdk/reference#events-for-playback-controls
                    "bufferend",
                    "bufferstart",
                    "ended",
                    "error",
                    "loaded",
                    "pause",
                    "play",
                    "playbackratechange",
                    "progress",
                    "seeked",
                    "timeupdate",
                    "volumechange",

                    // Events for chapters
                    // https://developer.vimeo.com/player/sdk/reference#events-for-chapters
                    "chapterchange",

                    // Events for cue points
                    // https://developer.vimeo.com/player/sdk/reference#events-for-cue-points
                    "cuechange",
                    "cuepoint",

                    // Events for text tracks
                    // https://developer.vimeo.com/player/sdk/reference#events-for-text-tracks
                    "texttrackchange",

                    // Events for interactive videos
                    // https://developer.vimeo.com/player/sdk/reference#events-for-interactive-videos
                    "interactivehotspotclicked",
                    "interactiveoverlaypanelclicked",
                ];

                vimeoEvents.forEach(function (vimeoEvent) {
                    player.on(vimeoEvent, (data) => dispatch(vimeoEvent, data));
                });
            },
        };
    });
});
