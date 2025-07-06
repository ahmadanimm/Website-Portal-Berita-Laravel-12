@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white rounded shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-pink-500 text-white rounded-lg p-2 mr-3">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Rekap Pelanggan</h2>
        </div>
        <div class="relative h-96">
            <canvas id="userChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <div class="flex items-center mb-4">
            <div class="bg-blue-500 text-white rounded-lg p-2 mr-3">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Rekap Kategori Artikel</h2>
        </div>
        <div class="relative h-96">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    
    function generateColors(length) {
        const colors = [];
        for (let i = 0; i < length; i++) {
            const hue = Math.floor(360 * i / length); 
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }

    const userChartCtx = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(userChartCtx, {
        type: 'doughnut',
        data: {
            labels: ['Umum', 'Premium'],
            datasets: [{
                data: [{{ $userCount - $premiumUsers }}, {{ $premiumUsers }}],
                backgroundColor: ['#3b82f6', '#38bdf8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#374151'
                    }
                }
            }
        }
    });

    const categoryLabels = {!! json_encode($categoryLabels) !!};
    const categoryData = {!! json_encode($categoryCounts) !!};
    const categoryColors = generateColors(categoryLabels.length);

    const categoryChartCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryChartCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: categoryColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#374151'
                    }
                }
            }
        }
    });
</script>
@endsection
