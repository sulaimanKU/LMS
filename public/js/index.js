
// charts.js
function initCharts() {
    // Dummy data
    const subjects = ['Math', 'Science', 'English', 'History'];
    const passData = [20, 18, 25, 22];
    const failData = [5, 7, 3, 6];
    // New calculated field: Total Students per Subject
    const overallTotal = subjects.map((_, i) => passData[i] + failData[i]);
    const averageScores = [75, 80, 70, 85];
    const assignments = [4, 6, 3, 5];

    // Helper function to sum arrays
    function sumArray(arr) {
        return arr.reduce((a, b) => a + b, 0);
    }

    // --- Modern Styling & Colors ---
    const primaryColor = 'rgba(54, 162, 235, 1)'; // Blue
    const successColor = 'rgba(75, 192, 192, 1)'; // Teal
    const dangerColor = 'rgba(255, 99, 132, 1)'; // Red/Pink
    const tertiaryColor = 'rgba(255, 159, 64, 1)'; // Orange

    // NOTE: This code assumes you have included the Chart.js Data Labels Plugin
    // in your HTML: <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


    // ==========================================================
    // 1. Line Chart - Average Scores 📈
    // Purpose: Shows trend/comparison of average performance.
    // ==========================================================
    const lineChartEl = document.getElementById('lineChart');
    if (lineChartEl) {
        new Chart(lineChartEl.getContext('2d'), {
            type: 'line',
            data: {
                labels: subjects,
                datasets: [{
                    label: 'Average Scores',
                    data: averageScores,
                    borderColor: primaryColor,
                    backgroundColor: primaryColor.replace('1)', '0.15)'),
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Subject Average Scores Trend' },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        min: 65,
                        max: 90,
                        title: { display: true, text: 'Score (%)' }
                    }
                }
            }
        });
    }

    // ==========================================================
    // 2. MIXED CHART (Bar & Line) - Total Students vs. Average Score 📊
    // Purpose: Correlates class size (bar) with performance (line).
    // FIX: Tooltip options adjusted for reliable hover interaction.
    // ==========================================================
    const barChartEl = document.getElementById('barChart');
    if (barChartEl) {
        new Chart(barChartEl.getContext('2d'), {
            type: 'bar',
            data: {
                labels: subjects,
                datasets: [
                    {
                        label: 'Total Students',
                        data: overallTotal,
                        backgroundColor: successColor.replace('1)', '0.8)'),
                        yAxisID: 'y',
                    },
                    {
                        label: 'Average Score (%)',
                        data: averageScores,
                        type: 'line',
                        borderColor: dangerColor,
                        backgroundColor: dangerColor.replace('1)', '0.2)'),
                        borderWidth: 3,
                        yAxisID: 'y1',
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Total Students vs. Average Score by Subject' },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        position: 'nearest'
                    },
                    datalabels: {
                        color: '#000',
                        anchor: 'end',
                        align: 'top'
                    }
                },
                scales: {
                    x: { title: { display: true, text: 'Subject' } },
                    y: { // Primary Axis (Left) for Total Students
                        position: 'left',
                        title: { display: true, text: 'Student Count' }
                    },
                    y1: { // Secondary Axis (Right) for Average Score
                        position: 'right',
                        title: { display: true, text: 'Average Score (%)' },
                        grid: { drawOnChartArea: false },
                        min: 60,
                        max: 100
                    }
                }
            }
        });
    }

    // ==========================================================
    // 3. Pie Chart - Assignment distribution 🥧
    // Purpose: Shows proportional breakdown of assignments by subject.
    // ==========================================================
    const pieChartEl = document.getElementById('pieChart');
    if (pieChartEl) {
        new Chart(pieChartEl.getContext('2d'), {
            type: 'pie',
            data: {
                labels: subjects,
                datasets: [{
                    data: assignments,
                    backgroundColor: [primaryColor, successColor, dangerColor, tertiaryColor],
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Assignment Distribution Across Subjects' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed !== null) {
                                    const total = sumArray(assignments);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1) + '%';
                                    label += context.parsed + ' assignments (' + percentage + ')';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // ==========================================================
    // 4. Doughnut Chart - Overall Pass/Fail 🍩
    // Purpose: Shows overall system health (total passes vs. total fails).
    // ==========================================================
    const overallPass = sumArray(passData);
    const overallFail = sumArray(failData);

    const doughnutChartEl = document.getElementById('doughnutChart');
    if (doughnutChartEl) {
        new Chart(doughnutChartEl.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Overall Pass', 'Overall Fail'],
                datasets: [{
                    data: [overallPass, overallFail],
                    backgroundColor: [successColor, dangerColor],
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Overall Performance Summary' }
                }
            }
        });
    }
}

// Initialize charts after DOM is ready
document.addEventListener("DOMContentLoaded", initCharts);

// -----collapse and menubar----

function initDashboardUI() {
    const appWrapper = document.getElementById('appWrapper');
    if (!appWrapper) return;

    const desktopToggleBtn = document.getElementById('desktopToggleBtn');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileOffcanvasEl = document.getElementById('mobileOffcanvas');

    // Desktop toggle
    if (desktopToggleBtn) {
        desktopToggleBtn.addEventListener('click', () => {
            appWrapper.classList.toggle('sidebar-collapsed');
        });
    }

    // Mobile offcanvas toggle
    if (mobileMenuBtn && mobileOffcanvasEl) {
        const mobileOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(mobileOffcanvasEl);
        mobileMenuBtn.addEventListener('click', () => mobileOffcanvas.toggle());

        // Auto-close offcanvas on large screens
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1200) {
                try { mobileOffcanvas.hide(); } catch(e) {}
            }
        });
    }
}

// Initialize dashboard UI after DOM is ready
document.addEventListener("DOMContentLoaded", initDashboardUI);
