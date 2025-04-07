                                // document.addEventListener("DOMContentLoaded", function () {
                                //     document.querySelectorAll(".custom-tooltip").forEach(function (tooltip) {
                                //         let associatedElement = tooltip.dataset.element;
                                //         document.querySelectorAll(associatedElement).forEach(function (el) {
                                //             el.addEventListener("mouseenter", function () {
                                //                 tooltip.querySelector(".tooltip-content").style.visibility = "visible";
                                //                 tooltip.querySelector(".tooltip-content").style.opacity = "1";
                                //             });
                                //             el.addEventListener("mouseleave", function () {
                                //                 tooltip.querySelector(".tooltip-content").style.visibility = "hidden";
                                //                 tooltip.querySelector(".tooltip-content").style.opacity = "0";
                                //             });
                                //         });
                                //     });
                                // });


                                document.addEventListener("DOMContentLoaded", function () {
                                    document.querySelectorAll(".tooltip-trigger").forEach(function (trigger) {
                                        let tooltip = trigger.querySelector(".tooltip-content");
                                
                                        trigger.addEventListener("mouseenter", function () {
                                            tooltip.style.visibility = "visible";
                                            tooltip.style.opacity = "1";
                                        });
                                
                                        trigger.addEventListener("mouseleave", function () {
                                            tooltip.style.visibility = "hidden";
                                            tooltip.style.opacity = "0";
                                        });
                                
                                        // Adjust positioning if tooltip overflows screen
                                        trigger.addEventListener("mousemove", function (event) {
                                            let tooltipRect = tooltip.getBoundingClientRect();
                                            let windowWidth = window.innerWidth;
                                
                                            if (tooltipRect.right > windowWidth) {
                                                tooltip.style.left = "auto";
                                                tooltip.style.right = "0";
                                            } else {
                                                tooltip.style.left = "50%";
                                                tooltip.style.right = "auto";
                                            }
                                        });
                                    });
                                });
                                
