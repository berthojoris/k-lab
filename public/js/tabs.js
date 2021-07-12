(() => {
 function a() {
     axios
         .get(route("today_check"))
         .then(function (a) {
             $.each(a.data, function (a, e) {
                 _.isEmpty(e.daily_input)
                     ? $("#" + e.short_name)
                           .removeClass("glowblue")
                           .addClass("glowred")
                     : $("#" + e.short_name)
                           .removeClass("glowred")
                           .addClass("glowblue");
             });
         })
         .catch(function (a) {
             console.error(a);
         });
 }
 function e() {
     var a = luxon.DateTime;
     $(".timeline-line").empty(),
         axios
             .get(route("notifikasi"))
             .then(function (e) {
                 _.isEmpty(e.data)
                     ? ($(".timeline-line").append("<p class='centered'>Belum ada polda yang mengirim data hari ini</p>"), $("#loadingPanel").addClass("invisible"), $("#dataPanel").removeClass("invisible"))
                     : $.each(e.data, function (e, t) {
                           var o = t.status,
                               n = t.created_at,
                               r =
                                   '\n                <div class="item-timeline timeline-new">\n                    <div class="t-dot">\n                        <div class="t-success">\n                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>\n                        </div>\n                    </div>\n                    <div class="t-content">\n                        <div class="t-uppercontent">\n                            <h5>' +
                                   t.polda.name +
                                   '</h5>\n                            <span class="">' +
                                   a.fromISO(n, { locale: "id" }).toRelative() +
                                   "</span>\n                        </div>\n                        <p>STATUS : " +
                                   o.toUpperCase() +
                                   "</p>\n                    </div>\n                </div>\n                ";
                           $(".timeline-line").append(r), $("#loadingPanel").addClass("invisible"), $("#dataPanel").removeClass("invisible");
                       });
             })
             .catch(function (a) {
                 console.error(a);
             });
 }
 function t() {
     $("#donut-chart").empty(),
         (donut = o(route("donut"))),
         donut.done(function (a, e, t) {
             var o = {
                 chart: { height: 350, fontFamily: "Bahnschrift", type: "donut", toolbar: { show: !1 } },
                 legend: {
                     show: !0,
                     showForSingleSeries: !1,
                     showForNullSeries: !0,
                     showForZeroSeries: !0,
                     position: "bottom",
                     horizontalAlign: "center",
                     floating: !1,
                     fontSize: "10px",
                     fontFamily: "Bahnschrift",
                     fontWeight: 400,
                     formatter: void 0,
                     inverseOrder: !1,
                     width: void 0,
                     height: void 0,
                     tooltipHoverFormatter: void 0,
                     offsetX: 0,
                     offsetY: 0,
                     labels: { colors: void 0, useSeriesColors: !1 },
                     markers: { width: 12, height: 12, strokeWidth: 0, strokeColor: "#fff", fillColors: void 0, radius: 12, customHTML: void 0, onClick: void 0, offsetX: 0, offsetY: 0 },
                     itemMargin: { horizontal: 0, vertical: 10 },
                     onItemClick: { toggleDataSeries: !0 },
                     onItemHover: { highlightDataSeries: !0 },
                 },
                 fill: { type: "gradient", gradient: { shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [50, 190, 100] } },
                 colors: ["#00adef", "#ea1c26"],
                 plotOptions: {
                     pie: {
                         donut: {
                             size: "65%",
                             background: "transparent",
                             labels: {
                                 show: !0,
                                 name: { show: !0, fontSize: "12px", fontFamily: "Bahnschrift", color: void 0, offsetY: -35 },
                                 value: {
                                     show: !0,
                                     fontSize: "60px",
                                     fontFamily: "Bahnschrift",
                                     color: "20",
                                     offsetY: 17,
                                     formatter: function (a) {
                                         return a + "%";
                                     },
                                 },
                                 total: {
                                     show: !0,
                                     showAlways: !1,
                                     label: "DATA MASUK",
                                     color: "#888ea8",
                                     formatter: function (a) {
                                         return a.globals.seriesTotals.reduce(function (a, e) {
                                             return a + "%";
                                         });
                                     },
                                 },
                             },
                         },
                     },
                 },
                 stroke: { show: !0, curve: "smooth", lineCap: "butt", colors: void 0, width: 1, dashArray: 0 },
                 series: [a.filled, a.nofilled],
                 labels: ["[ MASUK ]", "[ BELUM MASUK ]"],
                 responsive: [{ breakpoint: 500, options: { chart: { width: 300 }, legend: { position: "bottom" } } }],
             };
             new ApexCharts(document.querySelector("#donut-chart"), o).render();
         });
 }
 function o(a) {
     return (respObj = $.ajax({ url: a, data: "", dataType: "json", type: "GET" })), respObj;
 }
 jQuery(function () {
     var n = luxon.DateTime;
     n.now().setZone("Asia/Jakarta");
     n.now().setLocale("id");
     $(document).on("change", "#changeTheme", function (a) {
         $(this).is(":checked") ? console.log("mode terang") : console.log("mode gelap");
     }),
         $(document).on("click", "#filterOperasi", function (a) {
             a.preventDefault();
         }),
         $("#tbl_daily_submited tbody").on("click", ".previewPhro", function (a) {
             a.preventDefault();
             var e = $(this).attr("data-id");
             popupCenter({ url: route("previewPhroDashboard", { uuid: e }), title: "Detail", w: 1e3, h: 600 });
         }),
         t(),
         a(),
         e();
     var r = {
             chart: {
                 fontFamily: "Quicksand, sans-serif",
                 height: 365,
                 type: "area",
                 zoom: { enabled: !1 },
                 dropShadow: { enabled: !0, opacity: 0.3, blur: 5, left: -7, top: 22 },
                 toolbar: { show: !1 },
                 events: {
                     click: function (a, e, t) {
                         var o;
                         (o = t.dataPointIndex), popupCenter({ url: route("viewInputFromChart", { indexData: o }), title: "Detail", w: 750, h: 700 });
                     },
                 },
             },
             colors: ["#1490cb"],
             dataLabels: { enabled: !1 },
             noData: { text: "Loading Data", align: "center", verticalAlign: "middle", offsetX: 0, offsetY: 0, style: { color: "#ffffff", fontSize: "20px", fontFamily: "Quicksand, sans-serif" } },
             stroke: { show: !0, curve: "smooth", width: 2, lineCap: "square" },
             series: [{ name: "Total", data: [] }],
             xaxis: {
                 axisBorder: { show: !1 },
                 axisTicks: { show: !1 },
                 crosshairs: { show: !0 },
                 labels: { offsetX: 0, offsetY: 0, style: { fontSize: "12px", fontFamily: "Quicksand, sans-serif", cssClass: "apexcharts-xaxis-title" } },
                 categories: [],
             },
             yaxis: {
                 labels: {
                     formatter: function (a, e) {
                         return a;
                     },
                     offsetX: -10,
                     offsetY: 0,
                     style: { fontSize: "12px", fontFamily: "Quicksand, sans-serif", cssClass: "apexcharts-yaxis-title" },
                 },
             },
             grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
             legend: {
                 position: "top",
                 horizontalAlign: "right",
                 offsetY: -50,
                 fontSize: "16px",
                 fontFamily: "Quicksand, sans-serif",
                 markers: { width: 10, height: 10, strokeWidth: 0, strokeColor: "#fff", fillColors: void 0, radius: 12, onClick: void 0, offsetX: 0, offsetY: 0 },
                 itemMargin: { horizontal: 0, vertical: 20 },
             },
             tooltip: { theme: "dark", marker: { show: !0 }, x: { show: !1 } },
             fill: { type: "gradient", gradient: { type: "vertical", shadeIntensity: 1, inverseColors: !1, opacityFrom: 0.28, opacityTo: 0.05, stops: [45, 100] } },
             responsive: [{ breakpoint: 575, options: { legend: { offsetY: -30 } } }],
         },
         i = new ApexCharts(document.querySelector("#incoming_report"), r);
     i.render();
     var l = new ApexCharts(document.querySelector("#total_laphar_pelanggaran_lalin"), {
         chart: { fontFamily: "Quicksand, sans-serif", height: 365, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26"],
         plotOptions: { bar: { horizontal: false, columnWidth: "15%", barHeight: '15%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         noData: { text: "Loading Data", align: "center", verticalAlign: "middle", offsetX: 0, offsetY: 0, style: { color: "#ffffff", fontSize: "15px", fontFamily: "Quicksand, sans-serif" } },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     l.render();
     var s = new ApexCharts(document.querySelector("#total_laphar_kecelakaan_lalin"), {
         chart: { fontFamily: "Quicksand, sans-serif", height: 365, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26", "#fd7e14", "#ffc107"],
         plotOptions: { bar: { horizontal: false, columnWidth: "30%", barHeight: '30%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         noData: { text: "Loading Data", align: "center", verticalAlign: "middle", offsetX: 0, offsetY: 0, style: { color: "#ffffff", fontSize: "15px", fontFamily: "Quicksand, sans-serif" } },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
        //  fill: {
        //   type: 'gradient',
        //   gradient: {
        //     shade: 'dark',
        //     type: "vertical",
        //     shadeIntensity: 0.5,
        //     gradientToColors: undefined, // optional, if not defined - uses the shades of same color in series
        //     inverseColors: false,
        //     opacityFrom: 1,
        //     opacityTo: 1,
        //     stops: [0, 50, 100],
        //     colorStops: []
        //   }
        // }
     });
     s.render();
     var d = new ApexCharts(document.querySelector("#total_laphar_pelanggaran_lalin_all_project"), {
         chart: { fontFamily: "Quicksand, sans-serif", height: 365, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26"],
         plotOptions: { bar: { horizontal: false, columnWidth: "15%", barHeight: '15%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         noData: { text: "Loading Data", align: "center", verticalAlign: "middle", offsetX: 0, offsetY: 0, style: { color: "#ffffff", fontSize: "15px", fontFamily: "Quicksand, sans-serif" } },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     d.render();
     var c = new ApexCharts(document.querySelector("#total_laphar_kecelakaan_lalin_all_project"), {
         chart: { fontFamily: "Quicksand, sans-serif", height: 365, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26", "#fd7e14", "#ffc107"],
         plotOptions: { bar: { horizontal: false, columnWidth: "30%", barHeight: '30%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         noData: { text: "Loading Data", align: "center", verticalAlign: "middle", offsetX: 0, offsetY: 0, style: { color: "#ffffff", fontSize: "15px", fontFamily: "Quicksand, sans-serif" } },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     c.render();
     var h = new ApexCharts(document.querySelector("#anev_pelanggaran_lalin"), {
         chart: { height: 350, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26"],
         plotOptions: { bar: { horizontal: false, columnWidth: "25%", barHeight: '25%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     h.render();
     var p = new ApexCharts(document.querySelector("#anev_kecelakaan_lalin"), {
         chart: { height: 350, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26", "#fd7e14", "#ffc107"],
         plotOptions: { bar: { horizontal: false, columnWidth: "55%", barHeight: '55%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     p.render();
     var u = new ApexCharts(document.querySelector("#anev_pelanggaran_lalin_total"), {
         chart: { height: 350, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26"],
         plotOptions: { bar: { horizontal: false, columnWidth: "25%", barHeight: '25%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     u.render();
     var f = new ApexCharts(document.querySelector("#anev_kecelakaan_lalin_total"), {
         chart: { height: 350, type: "bar", toolbar: { show: !1 } },
         colors: ["#00adef", "#ea1c26", "#fd7e14", "#ffc107"],
         plotOptions: { bar: { horizontal: false, columnWidth: "55%", barHeight: '55%', endingShape: "rounded" } },
         grid: { borderColor: "#12455C", strokeDashArray: 0, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } }, padding: { top: 0, right: 0, bottom: 0, left: 10 } },
         dataLabels: { enabled: !1 },
         stroke: { show: !0, width: 2, colors: ["transparent"] },
         series: [],
         xaxis: { categories: [] },
         yaxis: { title: { text: "Total" } },
         // fill: { opacity: 1 },
         fill: { type: "gradient", gradient: { type: "vertical", inverseColors: false, shadeIntensity: 0.8, opacityFrom: 0.9, opacityTo: 0.9, stops: [0, 190, 100] } },
     });
     f.render(),
         setInterval(function () {
             (dashboardChart = o(route("dashboardChart"))),
                 dashboardChart.done(function (a, e, t) {
                     var o = a.rangeDate,
                         n = a.totalPerDate,
                         r = a.projectName;
                     _.isEmpty(o) ||
                         _.isEmpty(n) ||
                         ($("#projectName").html("" + r),
                         i.updateOptions({ xaxis: { labels: { offsetX: 0, offsetY: 5, style: { fontSize: "12px", fontFamily: "Quicksand, sans-serif", cssClass: "apexcharts-xaxis-title" } }, categories: o } }),
                         i.updateSeries([{ name: "Total", data: n }]));
                 }),
                 (chart_laphar = o(route("chart_laphar"))),
                 chart_laphar.done(function (a, e, t) {
                     l.updateOptions({ xaxis: { categories: [a.year] } }),
                         l.updateSeries([
                             { name: "Tilang", data: [a.tilang] },
                             { name: "Teguran", data: [a.teguran] },
                         ]),
                         s.updateOptions({ xaxis: { categories: [a.year] } }),
                         s.updateSeries([
                             { name: "Jumlah", data: [a.jumlah_kejadian] },
                             { name: "Meninggal Dunia", data: [a.jumlah_korban_meninggal] },
                             { name: "Luka Berat", data: [a.jumlah_korban_luka_berat] },
                             { name: "Luka Ringan", data: [a.jumlah_korban_luka_ringan] },
                         ]);
                 }),
                 (chart_laphar_all_project = o(route("chart_laphar_all_project"))),
                 chart_laphar_all_project.done(function (a, e, t) {
                     d.updateOptions({ xaxis: { categories: [a.year] } }),
                         d.updateSeries([
                             { name: "Tilang", data: [a.tilang] },
                             { name: "Teguran", data: [a.teguran] },
                         ]),
                         c.updateOptions({ xaxis: { categories: [a.year] } }),
                         c.updateSeries([
                             { name: "Jumlah", data: [a.jumlah_kejadian] },
                             { name: "Meninggal Dunia", data: [a.jumlah_korban_meninggal] },
                             { name: "Luka Berat", data: [a.jumlah_korban_luka_berat] },
                             { name: "Luka Ringan", data: [a.jumlah_korban_luka_ringan] },
                         ]);
                 }),
                 (chart_anev = o(route("chart_anev"))),
                 chart_anev.done(function (a, e, t) {
                     h.updateOptions({ xaxis: { categories: [a.prev_year, a.year] } }),
                         h.updateSeries([
                             { name: "Tilang", data: [a.tilang_prev, a.teguran_prev] },
                             { name: "Teguran", data: [a.tilang, a.teguran] },
                         ]),
                         p.updateOptions({ xaxis: { categories: [a.prev_year, a.year] } }),
                         p.updateSeries([
                             { name: "Jumlah", data: [a.jumlah_kejadian_prev, a.jumlah_kejadian] },
                             { name: "Meninggal", data: [a.jumlah_korban_meninggal_prev, a.jumlah_korban_meninggal] },
                             { name: "Luka Berat", data: [a.jumlah_korban_luka_berat_prev, a.jumlah_korban_luka_berat] },
                             { name: "Luka Ringan", data: [a.jumlah_korban_luka_ringan_prev, a.jumlah_korban_luka_ringan] },
                         ]);
                 }),
                 (chart_anev_all = o(route("chart_anev_all"))),
                 chart_anev_all.done(function (a, e, t) {
                     u.updateOptions({ xaxis: { categories: [a.prev_year, a.year] } }),
                         u.updateSeries([
                             { name: "Tilang", data: [a.tilang_prev, a.teguran_prev] },
                             { name: "Teguran", data: [a.tilang, a.teguran] },
                         ]),
                         f.updateOptions({ xaxis: { categories: [a.prev_year, a.year] } }),
                         f.updateSeries([
                             { name: "Jumlah", data: [a.jumlah_kejadian_prev, a.jumlah_kejadian] },
                             { name: "Meninggal", data: [a.jumlah_korban_meninggal_prev, a.jumlah_korban_meninggal] },
                             { name: "Luka Berat", data: [a.jumlah_korban_luka_berat_prev, a.jumlah_korban_luka_berat] },
                             { name: "Luka Ringan", data: [a.jumlah_korban_luka_ringan_prev, a.jumlah_korban_luka_ringan] },
                         ]);
                 }),
                 t(),
                 a(),
                 e();
         }, $("meta[name=reload_time]").attr("content"));
 });
})();
