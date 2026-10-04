(function () {
	function qs(root, sel) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	function animateCounters(root) {
		qs(root, ".spae-count").forEach(function (el) {
			if (el.dataset.done === "1") {
				return;
			}
			var target = parseFloat(el.getAttribute("data-target") || "0");
			var start = 0;
			var duration = 900;
			var t0 = null;
			function tick(ts) {
				if (!t0) {
					t0 = ts;
				}
				var p = Math.min((ts - t0) / duration, 1);
				el.textContent = String(Math.round(start + (target - start) * p));
				if (p < 1) {
					window.requestAnimationFrame(tick);
				} else {
					el.dataset.done = "1";
				}
			}
			window.requestAnimationFrame(tick);
		});
	}

	function bindFaq(root) {
		qs(root, ".spae-faq__q").forEach(function (btn) {
			if (btn.dataset.bound === "1") {
				return;
			}
			btn.dataset.bound = "1";
			btn.addEventListener("click", function () {
				var open = btn.getAttribute("aria-expanded") === "true";
				var panel = btn.getAttribute("aria-controls")
					? document.getElementById(btn.getAttribute("aria-controls"))
					: btn.nextElementSibling;
				btn.setAttribute("aria-expanded", open ? "false" : "true");
				if (panel) {
					if (open) {
						panel.setAttribute("hidden", "hidden");
					} else {
						panel.removeAttribute("hidden");
					}
				}
			});
		});
	}

	function bindTabs(root) {
		qs(root, ".spae-tabs").forEach(function (wrap) {
			if (wrap.dataset.bound === "1") {
				return;
			}
			wrap.dataset.bound = "1";
			var tabs = qs(wrap, ".spae-tab");
			var panels = qs(wrap, ".spae-panel");
			tabs.forEach(function (tab) {
				tab.addEventListener("click", function () {
					var i = parseInt(tab.getAttribute("data-i"), 10) || 0;
					tabs.forEach(function (t, idx) {
						t.setAttribute("aria-selected", idx === i ? "true" : "false");
					});
					panels.forEach(function (p, idx) {
						if (idx === i) {
							p.removeAttribute("hidden");
						} else {
							p.setAttribute("hidden", "hidden");
						}
					});
				});
			});
		});
	}

	function bindCountdown(root) {
		qs(root, ".spae-cd").forEach(function (el) {
			if (el.dataset.bound === "1") {
				return;
			}
			el.dataset.bound = "1";
			function tick() {
				var end = Date.parse(el.getAttribute("data-end") || "");
				var diff = Math.max(0, end - Date.now());
				var s = Math.floor(diff / 1000);
				var d = Math.floor(s / 86400);
				s -= d * 86400;
				var h = Math.floor(s / 3600);
				s -= h * 3600;
				var m = Math.floor(s / 60);
				s -= m * 60;
				var map = { d: d, h: h, m: m, s: s };
				qs(el, "[data-u]").forEach(function (n) {
					n.textContent = String(map[n.getAttribute("data-u")] || 0);
				});
			}
			tick();
			setInterval(tick, 1000);
		});
	}

	function bindBeforeAfter(root) {
		qs(root, ".spae-ba__r").forEach(function (input) {
			if (input.dataset.bound === "1") {
				return;
			}
			input.dataset.bound = "1";
			var box = input.parentNode.querySelector(".spae-ba__b");
			input.addEventListener("input", function () {
				if (box) {
					box.style.width = input.value + "%";
				}
			});
		});
	}

	function bindTyped(root) {
		qs(root, ".spae-typed span[data-words]").forEach(function (el) {
			if (el.dataset.bound === "1") {
				return;
			}
			el.dataset.bound = "1";
			var words = [];
			try {
				words = JSON.parse(el.getAttribute("data-words") || "[]");
			} catch (e) {
				words = [];
			}
			if (!words.length) {
				return;
			}
			var i = 0;
			setInterval(function () {
				i = (i + 1) % words.length;
				el.textContent = words[i];
			}, 1800);
		});
	}

	function bindSlider(root) {
		qs(root, ".spae-ts").forEach(function (wrap) {
			if (wrap.dataset.bound === "1") {
				return;
			}
			wrap.dataset.bound = "1";
			var slides = qs(wrap, ".spae-quote");
			var i = 0;
			function show(n) {
				i = (n + slides.length) % slides.length;
				slides.forEach(function (s, idx) {
					if (idx === i) {
						s.removeAttribute("hidden");
					} else {
						s.setAttribute("hidden", "hidden");
					}
				});
			}
			qs(wrap, ".spae-ts__nav button").forEach(function (btn) {
				btn.addEventListener("click", function () {
					show(i + parseInt(btn.getAttribute("data-dir"), 10));
				});
			});
		});
	}

	function bindModal(root) {
		qs(root, ".spae-modal-open").forEach(function (btn) {
			if (btn.dataset.bound === "1") {
				return;
			}
			btn.dataset.bound = "1";
			var dialog = btn.parentNode.querySelector("dialog");
			btn.addEventListener("click", function () {
				if (dialog && dialog.showModal) {
					dialog.showModal();
				} else if (dialog) {
					dialog.setAttribute("open", "open");
				}
			});
			if (dialog) {
				var close = dialog.querySelector(".spae-modal-close");
				if (close) {
					close.addEventListener("click", function () {
						if (dialog.close) {
							dialog.close();
						} else {
							dialog.removeAttribute("open");
						}
					});
				}
			}
		});
	}

	function bindOffcanvas(root) {
		qs(root, ".spae-oc-open").forEach(function (btn) {
			if (btn.dataset.bound === "1") {
				return;
			}
			btn.dataset.bound = "1";
			var panel = btn.parentNode.querySelector(".spae-oc");
			btn.addEventListener("click", function () {
				if (panel) {
					panel.removeAttribute("hidden");
				}
			});
			if (panel) {
				var close = panel.querySelector(".spae-oc-close");
				if (close) {
					close.addEventListener("click", function () {
						panel.setAttribute("hidden", "hidden");
					});
				}
			}
		});
	}

	function bindCoupon(root) {
		qs(root, ".spae-coupon").forEach(function (btn) {
			if (btn.dataset.bound === "1") {
				return;
			}
			btn.dataset.bound = "1";
			btn.addEventListener("click", function () {
				var val = btn.getAttribute("data-copy") || btn.textContent;
				if (navigator.clipboard) {
					navigator.clipboard.writeText(val);
				}
				btn.textContent = "Copied";
				setTimeout(function () {
					btn.textContent = val;
				}, 1200);
			});
		});
	}

	function bindImageAcc(root) {
		qs(root, ".spae-iacc").forEach(function (wrap) {
			if (wrap.dataset.bound === "1") {
				return;
			}
			wrap.dataset.bound = "1";
			qs(wrap, ".spae-iacc__p").forEach(function (btn) {
				btn.addEventListener("click", function () {
					qs(wrap, ".spae-iacc__p").forEach(function (p) {
						p.classList.remove("is-on");
					});
					btn.classList.add("is-on");
				});
			});
		});
	}

	function bindClock(root) {
		qs(root, ".spae-clock").forEach(function (el) {
			if (el.dataset.bound === "1") {
				return;
			}
			el.dataset.bound = "1";
			function tick() {
				try {
					el.textContent = new Date().toLocaleTimeString(undefined, {
						timeZone: el.getAttribute("data-tz") || undefined,
						hour: "2-digit",
						minute: "2-digit",
						second: "2-digit",
					});
				} catch (e) {
					el.textContent = new Date().toLocaleTimeString();
				}
			}
			tick();
			setInterval(tick, 1000);
		});
	}

	function bindTop(root) {
		qs(root, ".spae-top").forEach(function (a) {
			if (a.dataset.bound === "1") {
				return;
			}
			a.dataset.bound = "1";
			a.addEventListener("click", function (e) {
				e.preventDefault();
				window.scrollTo({ top: 0, behavior: "smooth" });
			});
		});
	}

	function init(root) {
		animateCounters(root);
		bindFaq(root);
		bindTabs(root);
		bindCountdown(root);
		bindBeforeAfter(root);
		bindTyped(root);
		bindSlider(root);
		bindModal(root);
		bindOffcanvas(root);
		bindCoupon(root);
		bindImageAcc(root);
		bindClock(root);
		bindTop(root);
	}

	if (document.readyState !== "loading") {
		init(document);
	} else {
		document.addEventListener("DOMContentLoaded", function () {
			init(document);
		});
	}

	window.addEventListener("elementor/frontend/init", function () {
		if (window.elementorFrontend && window.elementorFrontend.hooks) {
			window.elementorFrontend.hooks.addAction("frontend/element_ready/widget", function ($scope) {
				var el = $scope && $scope[0] ? $scope[0] : document;
				init(el);
			});
		}
	});
})();
