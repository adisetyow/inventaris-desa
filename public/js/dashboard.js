// Dashboard JavaScript for Inventaris Desa

document.addEventListener("DOMContentLoaded", function () {
    // Initialize charts
    initInventarisChart();
    initKondisiChart();

    // Add event listeners
    addEventListeners();

    // Initialize tooltips
    initTooltips();
});

// Inventaris Bulanan Chart
function initInventarisChart() {
    const ctx = document.getElementById("inventarisChart");
    if (!ctx) return;

    const chartData = window.inventarisBulanan || [];
    const labels = chartData.map((item) => item.bulan);
    const data = chartData.map((item) => item.total);

    const inventarisChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Inventaris Masuk",
                    data: data,
                    backgroundColor: "rgba(78, 115, 223, 0.1)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        callback: function (value) {
                            return value + " item";
                        },
                    },
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)",
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                },
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    titleColor: "#fff",
                    bodyColor: "#fff",
                    borderColor: "rgba(78, 115, 223, 1)",
                    borderWidth: 1,
                    callbacks: {
                        label: function (context) {
                            return (
                                "Inventaris Masuk: " +
                                context.parsed.y +
                                " item"
                            );
                        },
                    },
                },
            },
            animation: {
                duration: 1000,
                easing: "easeInOutQuart",
            },
        },
    });

    // Store chart instance for potential updates
    window.inventarisChartInstance = inventarisChart;
}

// Kondisi Inventaris Chart
function initKondisiChart() {
    const ctx = document.getElementById("kondisiChart");
    if (!ctx) return;

    const kondisiData = window.kondisiStats || {};
    const labels = Object.keys(kondisiData);
    const data = Object.values(kondisiData);

    const colors = getKondisiColors(labels);

    const kondisiChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [
                {
                    data: data,
                    backgroundColor: colors.background,
                    hoverBackgroundColor: colors.hover,
                    borderWidth: 2,
                    borderColor: "#fff",
                    hoverBorderColor: "#fff",
                    hoverBorderWidth: 3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "70%",
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    titleColor: "#fff",
                    bodyColor: "#fff",
                    borderColor: "#fff",
                    borderWidth: 1,
                    callbacks: {
                        label: function (context) {
                            const total = context.dataset.data.reduce(
                                (a, b) => a + b,
                                0
                            );
                            const percentage = (
                                (context.parsed / total) *
                                100
                            ).toFixed(1);
                            return (
                                context.label +
                                ": " +
                                context.parsed +
                                " item (" +
                                percentage +
                                "%)"
                            );
                        },
                    },
                },
            },
            animation: {
                duration: 1000,
                easing: "easeInOutQuart",
            },
        },
    });

    // Store chart instance for potential updates
    window.kondisiChartInstance = kondisiChart;
}

// Get colors for kondisi chart
function getKondisiColors(labels) {
    const colorMap = {
        Baik: "#1cc88a",
        Rusak: "#e74a3b",
        "Perlu Perbaikan": "#f6c23e",
        "Kurang Baik": "#f6c23e",
    };

    const hoverColorMap = {
        Baik: "#17a673",
        Rusak: "#be2617",
        "Perlu Perbaikan": "#dda20a",
        "Kurang Baik": "#dda20a",
    };

    const background = labels.map((label) => colorMap[label] || "#858796");
    const hover = labels.map((label) => hoverColorMap[label] || "#6b6d7d");

    return { background, hover };
}

// Add event listeners
function addEventListeners() {
    // Refresh chart button
    document
        .getElementById("refreshChart")
        ?.addEventListener("click", function (e) {
            e.preventDefault();
            refreshDashboardData();
        });

    // Export chart button
    document
        .getElementById("exportChart")
        ?.addEventListener("click", function (e) {
            e.preventDefault();
            exportChartAsImage();
        });

    // Stats cards click events
    document.querySelectorAll(".stats-card").forEach((card) => {
        card.addEventListener("click", function () {
            const cardType =
                this.querySelector(".text-uppercase").textContent.trim();
            handleStatsCardClick(cardType);
        });
    });
}

// Initialize tooltips
function initTooltips() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Handle stats card click
function handleStatsCardClick(cardType) {
    let route = "";

    switch (cardType) {
        case "Total Inventaris":
            route = '{{ route("inventaris.index") }}';
            break;
        case "Total Nilai Inventaris":
            route = '{{ route("inventaris.index") }}';
            break;
        case "Total Kategori":
            route = '{{ route("kategori-inventaris.index") }}';
            break;
        case "Mutasi Bulan Ini":
            route = '{{ route("mutasi-inventaris.index") }}';
            break;
        default:
            return;
    }

    window.location.href = route;
}

// Export chart as image
function exportChartAsImage() {
    if (window.inventarisChartInstance) {
        const canvas = document.getElementById("inventarisChart");
        const dataURL = canvas.toDataURL("image/png");

        const link = document.createElement("a");
        link.download =
            "grafik-inventaris-" +
            new Date().toISOString().slice(0, 10) +
            ".png";
        link.href = dataURL;
        link.click();
    }
}

// Refresh dashboard data
function refreshDashboardData() {
    const cardBody = document.querySelector(".card-body");
    if (cardBody) {
        showLoading(cardBody);

        // Simulate API call (replace with actual fetch)
        setTimeout(() => {
            fetch("/dashboard/refresh")
                .then((response) => response.json())
                .then((data) => {
                    updateCharts(data);
                    hideLoading(cardBody);

                    // Show success toast
                    showToast("Data berhasil diperbarui", "success");
                })
                .catch((error) => {
                    console.error("Error:", error);
                    hideLoading(cardBody);
                    showToast("Gagal memperbarui data", "error");
                });
        }, 1000);
    }
}

// Update charts with new data
function updateCharts(data) {
    if (data.inventarisBulanan && window.inventarisChartInstance) {
        window.inventarisChartInstance.data.labels = data.inventarisBulanan.map(
            (item) => item.bulan
        );
        window.inventarisChartInstance.data.datasets[0].data =
            data.inventarisBulanan.map((item) => item.total);
        window.inventarisChartInstance.update();
    }

    if (data.kondisiStats && window.kondisiChartInstance) {
        const labels = Object.keys(data.kondisiStats);
        const values = Object.values(data.kondisiStats);
        const colors = getKondisiColors(labels);

        window.kondisiChartInstance.data.labels = labels;
        window.kondisiChartInstance.data.datasets[0].data = values;
        window.kondisiChartInstance.data.datasets[0].backgroundColor =
            colors.background;
        window.kondisiChartInstance.data.datasets[0].hoverBackgroundColor =
            colors.hover;
        window.kondisiChartInstance.update();
    }
}

// Show loading state
function showLoading(element) {
    element.classList.add("loading");
}

// Hide loading state
function hideLoading(element) {
    element.classList.remove("loading");
}

// Show toast notification
function showToast(message, type = "success") {
    const toastContainer = document.createElement("div");
    toastContainer.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
    toastContainer.setAttribute("role", "alert");
    toastContainer.setAttribute("aria-live", "assertive");
    toastContainer.setAttribute("aria-atomic", "true");

    toastContainer.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    document.body.appendChild(toastContainer);

    const toast = new bootstrap.Toast(toastContainer, {
        autohide: true,
        delay: 3000,
    });

    toast.show();

    toastContainer.addEventListener("hidden.bs.toast", function () {
        document.body.removeChild(toastContainer);
    });
}

// Handle window resize
window.addEventListener("resize", function () {
    if (window.inventarisChartInstance) {
        window.inventarisChartInstance.resize();
    }
    if (window.kondisiChartInstance) {
        window.kondisiChartInstance.resize();
    }
});

// Auto-refresh data every 5 minutes (optional)
function startAutoRefresh() {
    setInterval(function () {
        refreshDashboardData();
    }, 300000); // 5 minutes
}

// Start auto-refresh when page loads
// startAutoRefresh();
