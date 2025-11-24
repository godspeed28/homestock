// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Nunito"),
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

// Pie Chart Example
fetch("/chart/top-category")
    .then((response) => response.json())
    .then((result) => {
        const ctx = document.getElementById("categoryPieChart");

        // Warna Pie
        const colors = ["#4e73df", "#1cc88a", "#36b9cc"];

        // === PIE CHART ===
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: result.labels,
                datasets: [
                    {
                        data: result.data,
                        backgroundColor: colors,
                        hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf"],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                cutoutPercentage: 80,
            },
        });

        // === LEGEND DINAMIS ===
        let legendHtml = "";
        result.labels.forEach((label, index) => {
            legendHtml += `
        <span class="mr-2">
          <i class="fas fa-circle" style="color:${colors[index]}"></i> ${label}
        </span>
      `;
        });

        document.getElementById("categoryLegend").innerHTML = legendHtml;
    });
