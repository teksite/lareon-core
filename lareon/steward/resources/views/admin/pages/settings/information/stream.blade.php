<x-lareon::admin-layout>
    @section('title', __('information'))
    @section('description', __('this page is a brief overview of the application and the system it is running on'))

    <x-lareon::editor.tabs.layout>
        <x-lareon::editor.tabs.link title="INFORMATION" :href="route('admin.settings.information.index')" />
        <x-lareon::editor.tabs.link title="STREAM" :href="route('admin.settings.information.stream')" />
    </x-lareon::editor.tabs.layout>

    <canvas id="system-chart"></canvas>

    @push('footerScripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            (() => {
                const CONFIG = {
                    endpoint: '/system-info/',
                    interval: 2000,
                    maxPoints: 60,
                    canvas: 'system-chart'
                };

                const api = async (path) => {
                    const response = await fetch(CONFIG.endpoint + path, {
                        cache: 'no-store'
                    });

                    if (!response.ok)throw new Error(`HTTP ${response.status}`);

                    return response.json();
                };

                // Combine CPU and RAM datasets
                const getDatasets = (charts) => [
                    ...charts.cpu.datasets.map(d => ({
                        ...d,
                        label: 'CPU - ' + d.label,
                        data: []
                    })),

                    ...charts.memory.datasets.map(d => ({
                        ...d,
                        label: 'RAM - ' + d.label,
                        data: []
                    }))
                ];

                // Append one metric point
                const addPoint = (chart, metrics) => {
                    const { cpu, memory } = metrics;

                    if (!cpu.labels?.length) return;

                    chart.data.labels.push(cpu.labels[0]);

                    const datasets = [...cpu.datasets, ...memory.datasets];

                    datasets.forEach((dataset, index) => {
                        chart.data.datasets[index].data.push(dataset.data?.[0] ?? null);
                    });

                    // Keep only the latest points
                    if (chart.data.labels.length > CONFIG.maxPoints) {
                        chart.data.labels.shift();

                        chart.data.datasets.forEach(dataset => {
                            dataset.data.shift();
                        });
                    }

                    chart.update('none');
                };

                // Create chart
                const createChart = (metrics) => {
                    const canvas = document.getElementById(CONFIG.canvas);

                    if (!canvas) return null;

                    // Prevent duplicate chart instances
                    Chart.getChart(canvas)?.destroy();

                    return new Chart(canvas, {
                        type: 'line',

                        data: {
                            labels: [],
                            datasets: getDatasets(metrics)
                        },

                        options: {
                            responsive: true,
                            animation: false,

                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },

                            scales: {
                                x: {
                                    display: false
                                },

                                y: {
                                    min: 0,
                                    max: 100,
                                    title: {
                                        display: true,
                                        text: 'Usage (%)'
                                    }
                                }
                            }
                        }
                    });
                };

                // Main
                const init = async () => {
                    let chart;

                    try {
                        // Initial metrics (no history)
                        const { charts } = await api('metrics?format=chartjs');

                        chart = createChart(charts);

                        if (!chart) return;

                        // Show first point immediately
                        addPoint(chart, charts);

                    } catch (error) {
                        console.error('Chart initialization failed:', error);
                        return;
                    }

                    // Poll metrics without overlapping requests
                    const poll = async () => {
                        try {
                            const { charts } = await api('metrics?format=chartjs');

                            addPoint(chart, charts);

                        } catch (error) {
                            console.error('Metrics update failed:', error);

                        } finally {
                            setTimeout(poll, CONFIG.interval);
                        }
                    };

                    setTimeout(poll, CONFIG.interval);
                };

                init();

            })();
        </script>
    @endpush

</x-lareon::admin-layout>
