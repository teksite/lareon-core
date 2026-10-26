let chartInstance = null;
let apexChartsPromise = null;

function loadApexCharts() {
    if (!apexChartsPromise) {
        apexChartsPromise = import('apexcharts')
            .then(module => module.default)
            .catch(error => {
                apexChartsPromise = null;
                throw error;
            });
    }

    return apexChartsPromise;
}

function initChart() {
    const chartForm = document.querySelector('#inboxLineChartForm');
    const inboxLineChart = document.querySelector('#inboxLineChart');

    if (!chartForm || !inboxLineChart) return;

    chartForm.addEventListener('submit', async e => {
        e.preventDefault();

        const fromDate = chartForm.querySelector('[name="fromDate"]').value;
        const toDate = chartForm.querySelector('[name="toDate"]').value;
        const range = chartForm.querySelector('[name="range"]').value || 'month';

        const params = new URLSearchParams({
            fromDate,
            toDate,
            range,
        });

        const url = `/tkadmin/ajax/questionnaire/analytics?${params}`;

        try {
            const res = await fetch(url);

            if (!res.ok)  throw new Error(`HTTP error: ${res.status}`);

            const result = await res.json();
            const data = result?.data;

            if (!Array.isArray(data?.series) ||
                !Array.isArray(data?.categories)) {
                console.error('Invalid chart data:', result);
                return;
            }

            const ApexCharts = await loadApexCharts();

            if (chartInstance) {
                chartInstance.destroy();
                chartInstance = null;
            }

            const options = {
                chart: {
                    type: 'area',
                    height: 600,
                    width: '100%',
                    animations: {
                        enabled: false,
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 1,
                },
                series: data.series,
                xaxis: {
                    categories: data.categories,
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: value => Math.round(value),
                    },
                },
                dataLabels: {
                    enabled: false,
                },
            };

            chartInstance = new ApexCharts(inboxLineChart, options);
            await chartInstance.render();

        } catch (err) {
            console.error('Error fetching or rendering chart:', err);
        }
    });
}

document.addEventListener('DOMContentLoaded', initChart);
