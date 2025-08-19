document.addEventListener("DOMContentLoaded", function () {
  const page = document.body.dataset.page || "index";
  const role = document.body.dataset.userRole || "GUEST";

  const dynamic_styles = [];

  // Login solo usa sweetalert2 extra
  if (page === "login") {
    dynamic_styles.push("../utilities/sweetalert2/sweetalert2.min.css");
  } else {
    dynamic_styles.push("../utilities/JqueryUI/jquery-ui.min.css");
    dynamic_styles.push("../utilities/sweetalert2/sweetalert2.min.css");
    dynamic_styles.push("../utilities/evo-calendar/css/evo-calendar.css");
    dynamic_styles.push("../utilities/evo-calendar/css/evo-calendar.royal-navy.css");

    if (role === "ADMIN") {
      // Admin styles
    }

    if (role === "PROJECT_MANAGER") {
      // PM styles
    }
  }

  dynamic_styles.forEach(href => {
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = href;
    document.head.appendChild(link);
  });

});
