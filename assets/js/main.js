
(function ($) {
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/photo-sphere-viewer.default",
      function (scope) {
        scope.find(".viewer").each(function () {
          var element = $(this)[0];
          var settings = $(this).data("settings");
          if (element) {
            new PhotoSphereViewer.Viewer(settings);
          }
        });
      }
    );
  });
})(jQuery);




// init shortcodes scripts
var PSV_Container = document.querySelectorAll(".xero-psv-container");
for (let i = 0; i < PSV_Container.length; i++) {
  let PSV_ID = PSV_Container[i].firstElementChild.getAttribute("id");
  PSV_ID = document.getElementById(PSV_ID);
  console.log(PSV_ID);
  const settings = PSV_ID.getAttribute("data-settings");
  if (PSV_ID) {
    new PhotoSphereViewer.Viewer(JSON.parse(settings));
  }
}