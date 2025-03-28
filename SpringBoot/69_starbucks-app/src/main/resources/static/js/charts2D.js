// ダッシュボード用のグラフ設定の初期値を定義
// - 色（企業カラー）と共通オプションを含む
const CHART_CONFIG = {
    colors: {
        primary: '#00704A',       // メインの緑色
        secondary: '#1E3932',    // サブの緑色
        tertiary: '#2D7F62',     // 補助的な緑色
        quaternary: '#4B9B7F',   // もう一つの補助色
    },
    commonOptions: {
        responsive: true,         // 画面サイズに応じてグラフをリサイズ
        plugins: {
            legend: {
                position: 'top',  // 凡例（ラベル）の位置を上部に設定
            },
        },
    },
};

// 全グラフの初期化を行う関数
// - 必要なデータを受け取り、それぞれのグラフを生成する
function initializeCharts(chartData) {
    const { salesData, productData, timeData, statusData } = chartData;

    initializeSalesChart(salesData);   // 売上推移グラフを初期化
    initializeProductChart(productData); // 商品別売上グラフを初期化
    initializeTimeChart(timeData);    // 時間帯別注文数グラフを初期化
    initializeStatusChart(statusData); // ステータス別注文数グラフを初期化
}

// 月間売上推移の折れ線グラフを初期化
function initializeSalesChart(salesData) {
    // 折れ線グラフの設定を作成
    const config = createLineChartConfig(
        '売上高',                  // ラベル（凡例に表示される）
        salesData.labels,          // 横軸ラベル（例: 月の名前）
        salesData.data,            // 売上データ
        CHART_CONFIG.colors.primary // グラフの色
    );

    // グラフを描画
    renderChart('salesChart', config);
}

// 商品別売上比率のドーナツグラフを初期化
function initializeProductChart(productData) {
    // ドーナツグラフの設定を作成
    const config = createDoughnutChartConfig(
        productData.labels,          // 商品名
        productData.data,            // 各商品の売上データ
        Object.values(CHART_CONFIG.colors) // 使用する色（配列に変換）
    );

    // グラフを描画
    renderChart('productSalesChart', config);
}

// 時間帯別注文数の棒グラフを初期化
function initializeTimeChart(timeData) {
    // 棒グラフの設定を作成
    const config = createBarChartConfig(
        '注文数',                   // ラベル
        timeData.labels,            // 時間帯（例: 7-9時）
        timeData.data,              // 注文数データ
        CHART_CONFIG.colors.primary // 棒の色
    );

    // グラフを描画
    renderChart('orderTimeChart', config);
}

// ステータス別注文数の円グラフを初期化
function initializeStatusChart(statusData) {
    // 円グラフの設定を作成
    const config = createPieChartConfig(
        statusData.labels,          // 注文ステータス（例: 準備中、完了）
        statusData.data,            // 各ステータスのデータ
        Object.values(CHART_CONFIG.colors) // 使用する色
    );

    // グラフを描画
    renderChart('orderStatusChart', config);
}

// 折れ線グラフの設定を作成する関数
function createLineChartConfig(label, labels, data, color) {
    return {
        type: 'line', // 折れ線グラフ
        data: {
            labels,    // 横軸ラベル
            datasets: [{
                label,  // グラフの凡例に表示
                data,   // データポイント
                borderColor: color, // 線の色
                backgroundColor: `${color}1A`, // 背景色（透明度10%）
                fill: true, // グラフの下部を塗りつぶす
            }],
        },
        options: {
            ...CHART_CONFIG.commonOptions, // 共通オプションを継承
            scales: {
                y: {
                    beginAtZero: true, // Y軸を0から始める
                    ticks: {
                        callback: (value) => `¥${value.toLocaleString()}`, // 値を通貨形式にフォーマット
                    },
                },
            },
        },
    };
}

// ドーナツグラフの設定を作成する関数
function createDoughnutChartConfig(labels, data, backgroundColors) {
    return {
        type: 'doughnut', // ドーナツグラフ
        data: {
            labels,    // グラフのラベル
            datasets: [{
                data,   // 各データ
                backgroundColor: backgroundColors, // 各セグメントの色
            }],
        },
        options: {
            ...CHART_CONFIG.commonOptions, // 共通オプションを継承
            plugins: {
                legend: {
                    position: 'right', // 凡例を右側に配置
                },
            },
        },
    };
}

// 棒グラフの設定を作成する関数
function createBarChartConfig(label, labels, data, color) {
    return {
        type: 'bar', // 棒グラフ
        data: {
            labels,    // 横軸ラベル
            datasets: [{
                label,  // グラフの凡例に表示
                data,   // データポイント
                backgroundColor: color, // 棒の色
            }],
        },
        options: {
            ...CHART_CONFIG.commonOptions, // 共通オプションを継承
            scales: {
                y: {
                    beginAtZero: true, // Y軸を0から始める
                    ticks: {
                        stepSize: 1, // 目盛り間隔を1に設定
                    },
                },
            },
        },
    };
}

// 円グラフの設定を作成する関数
function createPieChartConfig(labels, data, backgroundColors) {
    return {
        type: 'pie', // 円グラフ
        data: {
            labels,    // ラベル
            datasets: [{
                data,   // 各セグメントのデータ
                backgroundColor: backgroundColors, // セグメントの色
            }],
        },
        options: {
            ...CHART_CONFIG.commonOptions, // 共通オプションを継承
        },
    };
}

// グラフを描画する共通関数
function renderChart(elementId, config) {
    const ctx = document.getElementById(elementId); // 描画するHTML要素を取得
    new Chart(ctx, config); // Chart.jsでグラフを生成
}
