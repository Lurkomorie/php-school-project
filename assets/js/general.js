

var App = new Vue({
    el: '#nav',
    data: {
        rawHtml: '<nav class="navbar navbar-expand-lg navbar-light bg-light">\n' +
        '    <a class="navbar-brand" href="#">MyProj</a>\n' +
        '    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">\n' +
        '        <span class="navbar-toggler-icon"></span>\n' +
        '    </button>\n' +
        '    <div class="collapse navbar-collapse" id="navbarNavDropdown">\n' +
        '        <ul class="navbar-nav">\n' +
        '            <li class="nav-item active">\n' +
        '                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>\n' +
        '            </li>\n' +
        '        </ul>\n' +
        '    </div>\n'
    },
});

