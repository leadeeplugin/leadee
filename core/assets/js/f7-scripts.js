var globalTheme = 'light';

const app = new Framework7({
    el: '#app',
    theme: 'md',
    data: function () {
        return {
            theme: globalTheme
        }
    },
    methods: {},
    routes: []
});


var panel = app.panel.create({
    el: '.panel-left',
    resizable: true,
    visibleBreakpoint: 1023,
    collapsedBreakpoint: 768,
    swipePanel: false
})

var swiper = app.swiper.create('.swiper-mult', {
    pagination: '.swiper-mult .swiper-pagination',
    spaceBetween: 0,
    slidesPerView: "auto",
    //centeredSlides: true
});

app.progressbar.show();

window.onload = function () {
    document.body.classList.add('loaded');
    app.progressbar.hide();
}
