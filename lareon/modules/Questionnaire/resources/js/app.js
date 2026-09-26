import ApexCharts from 'apexcharts'


function initChart() {


    const chartForm = document.querySelector('#inboxLineChartForm');
    const inboxLineChart = document.querySelector('#inboxLineChart');

    if (!chartForm || !inboxLineChart) return;

    let chartInstance = null;
    chartForm.addEventListener('submit', async e => {
        e.preventDefault();

        const fromDate = chartForm.querySelector('[name="fromDate"]').value;
        const toDate = chartForm.querySelector('[name="toDate"]').value;
        const range = chartForm.querySelector('[name="range"]').value ?? 'month';

        const url = `/tkadmin/ajax/questionnaire/analytics?fromDate=${fromDate}&toDate=${toDate}&range=${range}`;

        try {
            const res = await fetch(url);
            const data = await res.json();

            if (!data?.data?.series || !data?.data?.categories) {
                console.error("Invalid chart data:", data);
                return;
            }

            if (chartInstance) {
                chartInstance.destroy();
            }

            const options = {
                chart: {
                    type: 'area',
                    height: 600,
                    width: '100%'
                },
                animations: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 1
                },
                series: data.data.series,
                xaxis: {
                    categories: data.data.categories
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: value => Math.round(value) // بدون اعشار
                    }
                },
                dataLabels: {
                    enabled: false
                }
            };

            chartInstance = new ApexCharts(inboxLineChart, options);
            await chartInstance.render();

        } catch (err) {
            console.error("Error Fetching Chart Data:", err);
        }
    });
}


document.addEventListener('DOMContentLoaded', function () {
    initChart();
});
