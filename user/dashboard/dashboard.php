<?php
include '../../conn.php';
include '../../components/header.php';
include '../../function.php';

Functions::startSessionIfNotStarted();
$database = new conn();
$conn = $database->conn;
$function = new Functions();

$user_status = 'offline';
$accountnum = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = Functions::getUserNum($conn, $user_id);
    if ($user) {
        $user_status = $user['status'];
        $user_role = $user['role'];
        Functions::redirectBasedOnRole($user_role);
        $accountnum = $user['accountnum'] ?? '';
    }
}

$billData = [];
$ledgerData = [];

if ($accountnum) {
    $billInquiryUrl = "http://10.0.1.247:8090/api/bill_inquiry/$accountnum";
    $ledgerUrl = "http://10.0.1.247:8090/api/ledger/$accountnum";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $billInquiryUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $billResponse = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ledgerUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ledgerResponse = curl_exec($ch);
    curl_close($ch);

    $billData = json_decode($billResponse, true)[0][0] ?? [];
    $ledgerData = json_decode($ledgerResponse, true)[0] ?? [];
}

function formatMonth($billingMonth) {
    return date("F", mktime(0, 0, 0, substr($billingMonth, 4, 2), 1, substr($billingMonth, 0, 4)));
}

$months = [];
$paidBills = [];
$unpaidBills = [];
$pendingBills = [];
$kWhUsed = [];

foreach ($ledgerData as $entry) {
    $monthName = formatMonth($entry['Billing Month']);
    if ($entry['Paid_Posted'] == 'YES') {
        $months[] = "$monthName (Paid)";
        $paidBills[] = $entry['Current Month Bill'];
        $unpaidBills[] = 0;
        $pendingBills[] = 0;
    } elseif ($entry['Paid_Posted'] == 'NO' && $entry['Paid_Unposted'] == 'YES') {
        $months[] = "$monthName (Pending)";
        $paidBills[] = 0;
        $unpaidBills[] = 0;
        $pendingBills[] = $entry['Current Month Bill'];
    } else {
        $months[] = "$monthName (Unpaid)";
        $paidBills[] = 0;
        $unpaidBills[] = $entry['Current Month Bill'];
        $pendingBills[] = 0;
    }
    $kWhUsed[] = $entry['kWh Used'] ?? 0;
}

$months = array_slice(array_reverse($months), 0, 6);
$paidBills = array_slice(array_reverse($paidBills), 0, 6);
$unpaidBills = array_slice(array_reverse($unpaidBills), 0, 6);
$pendingBills = array_slice(array_reverse($pendingBills), 0, 6);
$kWhUsed = array_slice(array_reverse($kWhUsed), 0, 6);

Functions::includeNavbarBasedOnStatus($user_status);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-br from-gray-100 to-gray-300">
    <div class="flex-grow flex flex-col items-center gap-8 p-6 md:p-12">
        <!-- Top Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 w-full max-w-7xl">
            <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col items-center text-center card font-bold">
                <div class=" flex justify-between w-full">
                    <span class="text-sm md:text-base text-gray-700 mb-1">
                        Current
                        <?php
                        // Check if Billing Month exists and format it as a month name only
                        echo isset($billData['Billing Month'])
                            ? ' ' . htmlspecialchars(
                                is_numeric($billData['Billing Month'])
                                    ? date('F', strtotime($billData['Billing Month']))
                                    : $billData['Billing Month']
                            ) . ' Bill:'
                            : ' N/A Bill:';
                        ?>
                    </span>
                    <span class="text-sm md:text-base text-gray-700">
                        ₱
                        <?php
                        echo isset($billData['Current Month Bill'])
                            ? number_format((float)$billData['Current Month Bill'], 2)
                            : 'N/A';
                        ?>
                    </span>
                </div>
                <div class="flex justify-between w-full mb-1">
                    <span class="text-sm md:text-base text-gray-700">
                        Total Arrears :
                    </span>
                    <span class="text-sm md:text-base text-gray-700">
                        ₱
                        <?php
                        echo isset($billData['Total Arrears'])
                            ? number_format((float)$billData['Total Arrears'], 2)
                            : 'N/A';
                        ?>
                    </span>
                </div>
                <div class="flex justify-between w-full mb-1 text-orange-500">
                    <span class="text-sm md:text-base ">
                        Running Balance :
                    </span>
                    <span class="text-sm md:text-base">
                        ₱
                        <?php
                        echo isset($billData['Running Balance'])
                            ? number_format((float)$billData['Running Balance'], 2)
                            : '0.00';
                        ?>
                    </span>
                </div>
                <div class="flex justify-between w-full text-red-500">
                    <span class="text-sm md:text-base ">
                        Total Penalties :
                    </span>
                    <span class="text-sm md:text-base ">
                        ₱
                        <?php
                        echo isset($billData['Total Penalties'])
                            ? number_format((float)$billData['Total Penalties'], 2)
                            : '0.00';
                        ?>
                    </span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center flex flex-col items-center card">
                <i class="fas fa-plug text-4xl text-blue-500 mb-4"></i>
                <strong class="text-sm md:text-base text-gray-700">kWh Used</strong>
                <span class="text-lg md:text-xl font-bold text-gray-900"><?php echo htmlspecialchars($billData['kWh Used'] ?? 'N/A'); ?></span>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center flex flex-col items-center card">
                <i class="fas fa-calendar-alt text-4xl text-green-500 mb-4"></i>
                <strong class="text-sm md:text-base text-gray-700">Billing Month</strong>
                <span class="text-lg md:text-xl font-bold text-gray-900">
                    <?php
                    $billingMonth = isset($billData['Billing Month']) ? formatMonth($billData['Billing Month']) : 'N/A';
                    $latestLedgerEntry = null;
                    foreach ($ledgerData as $entry) {
                        if ($entry['Billing Month'] === $billData['Billing Month']) {
                            $latestLedgerEntry = $entry;
                            break;
                        }
                    }
                    if ($latestLedgerEntry) {
                        $paidPosted = $latestLedgerEntry['Paid_Posted'] ?? 'NO';
                        $paidUnposted = $latestLedgerEntry['Paid_Unposted'] ?? 'NO';
                        $status = $paidPosted == 'YES' ? 'Paid' : ($paidUnposted == 'YES' ? 'Pending' : 'Unpaid');
                    } else {
                        $status = 'N/A';
                    }
                    echo htmlspecialchars("$billingMonth ($status)");
                    ?>
                </span>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center flex flex-col items-center card">
                <i class="fas fa-clock text-4xl text-red-500 mb-4"></i>
                <strong class="text-sm md:text-base text-gray-700">Due Date</strong>
                <span class="text-lg md:text-xl font-bold text-gray-900"><?php echo isset($billData['Due Date']) ? date("F d, Y", strtotime($billData['Due Date'])) : 'N/A'; ?></span>
            </div>
        </div>

        <!-- Main Content: Chart and Sidebar -->
        <div class="w-full max-w-7xl flex flex-col md:flex-row gap-6">
            <!-- Chart Wrapper -->
            <div class="bg-white p-8 rounded-xl shadow-lg flex-grow h-96">
                <div id="billChartContainer" class="h-full">
                    <canvas id="electricConsumptionChart" class="h-full"></canvas>
                    <div class="mt-6 p-4  rounded-lg text-center">
                        <p><strong class="text-gray-700">Reminder:</strong> <span class="text-green-500 font-bold">Green bars</span> indicate paid bills, <span class="text-yellow-500 font-bold">Yellow bars</span> indicate pending bills, while <span class="text-red-600 font-bold">Red bars</span> indicate unpaid bills.</p>
                    </div>
                </div>
                <div id="kWhChartContainer" class="hidden h-full">
                    <canvas id="kWhConsumptionChart" class="h-full"></canvas>
                    <div class="mt-6 p-4  rounded-lg text-center">
                        <p><strong class="text-gray-700">Reminder:</strong> Each line represents kWh used for a specific month.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toggle Button -->
        <button id="toggleChartButton" class="mt-6 px-6 py-3 text-white rounded-lg shadow-lg bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-indigo-600 hover:to-blue-500 transition-all duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl">Switch to kWh Chart</button>
    </div>
    <?php include '../../components/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxBill = document.getElementById('electricConsumptionChart').getContext('2d');
            const ctxKWh = document.getElementById('kWhConsumptionChart').getContext('2d');
            const toggleButton = document.getElementById('toggleChartButton');
            const billChartContainer = document.getElementById('billChartContainer');
            const kWhChartContainer = document.getElementById('kWhChartContainer');

            const months = <?php echo json_encode($months); ?>;
            const paidBills = <?php echo json_encode($paidBills); ?>;
            const unpaidBills = <?php echo json_encode($unpaidBills); ?>;
            const pendingBills = <?php echo json_encode($pendingBills); ?>;
            const kWhUsed = <?php echo json_encode($kWhUsed); ?>;

            // Bar Chart for Bills
            new Chart(ctxBill, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Paid (₱)',
                            data: paidBills,
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pending (₱)',
                            data: pendingBills,
                            backgroundColor: 'rgba(234, 179, 8, 0.8)',
                            borderColor: 'rgba(234, 179, 8, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Unpaid (₱)',
                            data: unpaidBills,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' }
                    }
                }
            });

            // Rainbow colors for kWh chart
            const rainbowColors = [
                'rgba(255, 0, 0, 0.8)',     // Red
                'rgba(255, 127, 0, 0.8)',   // Orange
                'rgba(255, 255, 0, 0.8)',   // Yellow
                'rgba(0, 255, 0, 0.8)',     // Green
                'rgba(0, 0, 255, 0.8)',     // Blue
                'rgba(148, 0, 211, 0.8)'    // Purple
            ];

            // Line Chart for kWh with rainbow colors and bigger dots
            new Chart(ctxKWh, {
                type: 'line',
                data: {
                    labels: months.map(month => month.split(' ')[0]),
                    datasets: [{
                        label: 'kWh Used',
                        data: kWhUsed,
                        borderColor: 'rgba(86, 86, 86, 0.3)', // Light gray line connecting points
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 8,           // Larger dots
                        pointHoverRadius: 10,     // Even larger when hovered
                        pointBackgroundColor: months.map((_, index) => rainbowColors[index % rainbowColors.length]),
                        pointBorderColor: 'rgba(86, 86, 86, 0.3)',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                boxWidth: 20,
                                font: { size: 14 }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `${months[tooltipItem.dataIndex]}: ${tooltipItem.raw} kWh`;
                                }
                            }
                        }
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Months'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'kWh Used'
                            }
                        }
                    }
                }
            });

            // Toggle Button Functionality
            toggleButton.addEventListener('click', function () {
                if (billChartContainer.classList.contains('hidden')) {
                    billChartContainer.classList.remove('hidden');
                    kWhChartContainer.classList.add('hidden');
                    toggleButton.textContent = 'Switch to kWh Chart';
                } else {
                    billChartContainer.classList.add('hidden');
                    kWhChartContainer.classList.remove('hidden');
                    toggleButton.textContent = 'Switch to Bill Chart';
                }
            });
        });
    </script>
</body>
</html>
