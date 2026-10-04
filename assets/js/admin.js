(function () {
  function cards() {
    return Array.prototype.slice.call(document.querySelectorAll(".spae-wcard"));
  }
  function apply() {
    var q = (document.getElementById("spae-search") || {}).value || "";
    q = q.toLowerCase();
    var group = document.querySelector(".spae-pillbtn.is-on");
    var g = group ? group.getAttribute("data-group") : "all";
    cards().forEach(function (card) {
      var text = (card.getAttribute("data-search") || "").toLowerCase();
      var okQ = !q || text.indexOf(q) !== -1;
      var okG = g === "all" || card.getAttribute("data-group") === g;
      card.style.display = okQ && okG ? "" : "none";
    });
  }
  document.addEventListener("click", function (e) {
    var pill = e.target.closest(".spae-pillbtn");
    if (pill) {
      document.querySelectorAll(".spae-pillbtn").forEach(function (p) { p.classList.remove("is-on"); });
      pill.classList.add("is-on");
      apply();
    }
    if (e.target.id === "spae-all") {
      cards().forEach(function (card) {
        var input = card.querySelector("input");
        if (input && card.style.display !== "none") input.checked = true;
        card.classList.toggle("is-off", !(input && input.checked));
      });
    }
    if (e.target.id === "spae-none") {
      cards().forEach(function (card) {
        var input = card.querySelector("input");
        if (input && card.style.display !== "none") input.checked = false;
        card.classList.toggle("is-off", !(input && input.checked));
      });
    }
  });
  document.addEventListener("input", function (e) {
    if (e.target && e.target.id === "spae-search") apply();
    if (e.target && e.target.classList.contains("spae-toggle")) {
      e.target.closest(".spae-wcard").classList.toggle("is-off", !e.target.checked);
    }
  });
})();
