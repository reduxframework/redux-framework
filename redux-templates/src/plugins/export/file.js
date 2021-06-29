const block_export_json = function (el, type) {
    if (!el) {
        return
    }

    if (el) {
        t ? t += '.json' : t = 'block.json', 'object' === ('undefined' === typeof e ? 'undefined' : u(e)) && (el = 1 === a.count ? JSON.stringify(e.shift(), void 0, 4) : JSON.stringify(e, void 0, 4));
        var n = new Blob([el], {
                type: 'text/json'
            }),
            o = document.createEvent('MouseEvents'),
            l = document.createElement('a');
        l.download = t, l.href = window.URL.createObjectURL(n), l.dataset.downloadurl = ['text/json', l.download, l.href].join(':'), o.initMouseEvent('click', !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), l.dispatchEvent(o)
    }
}

const block_export_html = function (el, type) {
    if (!el) {
        return
    }

    if (el) {
        t ? t += '.json' : t = 'block.json', 'object' === ('undefined' === typeof e ? 'undefined' : u(e)) && (el = 1 === a.count ? JSON.stringify(e.shift(), void 0, 4) : JSON.stringify(e, void 0, 4));
        var n = new Blob([el], {
                type: 'text/json'
            }),
            o = document.createEvent('MouseEvents'),
            l = document.createElement('a');
        l.download = t, l.href = window.URL.createObjectURL(n), l.dataset.downloadurl = ['text/json', l.download, l.href].join(':'), o.initMouseEvent('click', !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), l.dispatchEvent(o)
    }
}

const block_export_page = function (el, type) {
    if (!el) {
        return
    }

    if (el) {
        t ? t += '.json' : t = 'block.json', 'object' === ('undefined' === typeof e ? 'undefined' : u(e)) && (el = 1 === a.count ? JSON.stringify(e.shift(), void 0, 4) : JSON.stringify(e, void 0, 4));
        var n = new Blob([el], {
                type: 'text/json'
            }),
            o = document.createEvent('MouseEvents'),
            l = document.createElement('a');
        l.download = t, l.href = window.URL.createObjectURL(n), l.dataset.downloadurl = ['text/json', l.download, l.href].join(':'), o.initMouseEvent('click', !0, !1, window, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, null), l.dispatchEvent(o)
    }
}

/**
 * Downloads a file.
 *
 * @param {string} fileName    File Name.
 * @param {string} content     File Content.
 * @param {string} contentType File mime type.
 */
export function download( fileName, content, contentType ) {
    const file = new window.Blob( [ content ], { type: contentType } );

    // IE11 can't use the click to download technique
    // we use a specific IE11 technique instead.
    if ( window.navigator.msSaveOrOpenBlob ) {
        window.navigator.msSaveOrOpenBlob( file, fileName );
    } else {
        const a = document.createElement( 'a' );
        a.href = URL.createObjectURL( file );
        a.download = fileName;

        a.style.display = 'none';
        document.body.appendChild( a );
        a.click();
        document.body.removeChild( a );
    }
}
