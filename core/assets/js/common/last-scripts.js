(function ($) {
    $('#sidebar-links a').each(function () {
        const link_page = $(this).attr('href');
        const urlLinkUrlParams = new URLSearchParams(link_page.split('?')[1]);
        const urlParams = new URLSearchParams(window.location.search);
        if (urlLinkUrlParams.get('leadee-page') === urlParams.get('leadee-page')) {
            $(this).addClass('active');
        }
    });
})(jQuery);

needTour = false;
document.getElementById('start-tour').addEventListener('click', setOpenTour);

window.setInterval(() => checkForTour(), 300);

function setOpenTour() {
    needTour = true;
}

function checkForTour() {
    if (needTour === true) {
        openTour();
        needTour = false;
    }
}

function drawLeadeeNews() {
    fetch(LEADEE_NEWS_URL)
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
        })
        .then(function(data) {
            var htmlNews = "";
            for (var i = 0; i < data.length; i++) {
                htmlNews += getNewsHtml(data[i], i);
            }
            document.getElementById("news-feed").innerHTML = htmlNews;
        })
        .catch(function(error) {
            console.log('Request failed:', error);
        });
}

function getNewsHtml(newsItemdata, i) {
    const title = `<div class="news-title">
                    <a href="${newsItemdata.link}" rel="noreferrer" target="_blank">${newsItemdata.title}</a>
                  </div>`;
    const description = `<div class="news-body">
                        <p>${newsItemdata.description}</p>
                      </div>`;
    const footer = `<div class="news-footer">
                    <a href="${newsItemdata.link}" rel="noreferrer" target="_blank"> Read More »</a>
                    <span class="news-date">${newsItemdata.createdAt}</span>
                  </div>`;
    const news = `<li class="news-item">
                    ${title}
                    ${(i === 0) ? description : ""}
                    ${footer}
                 </li>\n`
    return news;
}

drawLeadeeNews();

