/**
 * ダッシュボード用の3Dグラフを初期化する
 * @param {Object} chartData - 全グラフのデータを含むオブジェクト
 * @param {Object} chartData.salesData - 売上データ
 * @param {Object} chartData.productData - 商品別データ
 * @param {Object} chartData.timeData - 時間帯別データ
 * @param {Object} chartData.statusData - ステータス別データ
 */

function initializeThreeCharts(chartData) {
    initializeSalesChart3D(chartData.salesData);
    initializeProductChart3D(chartData.productData);
    initializeTimeChart3D(chartData.timeData);
    initializeStatusChart3D(chartData.statusData);
}

/**
 * 月間売上推移の3D棒グラフを初期化する
 * @param {Object} salesData - 売上データ
 * @param {Array<number>} salesData.data - 月別売上データの配列
 */
function initializeSalesChart3D(salesData) {
    // DOM要素の取得（グラフを描画するHTMLコンテナ）
    const container = document.getElementById('salesChart3D');

    // Three.jsのシーン（描画領域）を作成
    const scene = new THREE.Scene();

    // カメラを初期化（75度の視野角、描画領域のアスペクト比を設定）
    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);

    // WebGLレンダラー（ブラウザでの描画処理）を設定
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    // OrbitControlsの追加
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true; // スムーズなアニメーション
    controls.dampingFactor = 0.05;
    controls.minDistance = 5; // 最小ズーム距離
    controls.maxDistance = 20; // 最大ズーム距離
    controls.maxPolarAngle = Math.PI / 2; // 地面より下にカメラが行かないように制限

    // 背景色を白に設定
    renderer.setClearColor(0xffffff);

    // カメラを配置（X:5, Y:5, Z:10の位置に設置し、原点を見下ろす）
    camera.position.set(5, 5, 10);
    camera.lookAt(0, 0, 0);

    // グリッドを追加（3D空間内の目安線）
    const gridHelper = new THREE.GridHelper(10, 10);
    scene.add(gridHelper);

    // 最大売上を取得（高さのスケールに利用）
    const maxSales = Math.max(...salesData.data);
    const minHeight = 0.5; // 最低高さを設定（見やすさ向上）

    // Raycasterとマウスベクトルを定義
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();

    // 売上データをもとに3D棒グラフを生成
    salesData.data.forEach((value, index) => {
        // 棒グラフの高さをデータ値から計算
        const height = (value / maxSales) * 5 + minHeight;

        // 色を動的に設定（売上に応じて濃淡を変化）
        const colorIntensity = Math.floor((value / maxSales) * 255);
        const color = new THREE.Color(`rgb(0, ${100 + colorIntensity}, ${74 + colorIntensity})`);

        // 棒の形状を定義（幅・奥行:0.5、高さ:データ値）
        const geometry = new THREE.BoxGeometry(0.5, height, 0.5);
        const material = new THREE.MeshPhongMaterial({ color });
        const bar = new THREE.Mesh(geometry, material);

        // 棒の位置を設定（X方向:間隔を空けて配置、Y方向:高さの中心に位置）
        bar.position.x = index - (salesData.data.length / 2);
        bar.position.y = height / 2;
        // ユーザーデータとして売上詳細を格納（追加部分）
        bar.userData = {
            month: salesData.labels[index],
            sales: value
        };

        // シーンに追加
        scene.add(bar);

        // 月名ラベルを追加
        const loader = new THREE.FontLoader();
        loader.load(
            'https://threejs.org/examples/fonts/helvetiker_regular.typeface.json', // フォントデータのURL
            function (font) {
                // ラベルの形状を作成
                const textGeometry = new THREE.TextGeometry(salesData.labels[index], {
                    font: font,
                    size: 0.2, // 文字サイズ
                    height: 0.05 // 文字の厚み
                });
                // ラベル用のマテリアル（黒色）
                const textMaterial = new THREE.MeshBasicMaterial({ color: 0x000000 });
                const text = new THREE.Mesh(textGeometry, textMaterial);

                // ラベルの位置を設定（棒の下に配置）
                text.position.set(bar.position.x, -0.5, 0); // 追加部分
                scene.add(text);
            }
        );
    });

    // 棒をクリックして詳細表示
    container.addEventListener('click', (event) => {
        // マウス座標を正規化
        const rect = container.getBoundingClientRect();
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        // Raycasterでクリックされたオブジェクトを取得
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(scene.children);

        if (intersects.length > 0) {
            const clickedObject = intersects[0].object;

            // 棒のデータを取得して表示
            if (clickedObject.userData) {
                alert(`月: ${clickedObject.userData.month}\n売上: ¥${clickedObject.userData.sales.toLocaleString()}`);
            }
        }
    });

    // ライト（光源）の設定
    const light = new THREE.DirectionalLight(0xffffff, 1);
    light.position.set(5, 5, 5);
    scene.add(light);
    scene.add(new THREE.AmbientLight(0x404040)); // 環境光を追加

    // アニメーション処理（シーンをY軸回転）
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();
}

/**
 * 商品別売上比率の3D円グラフを初期化する
 * @param {Object} productData - 商品別売上データ
 * @param {Array<number>} productData.data - 商品別の売上比率（%）
 */
function initializeProductChart3D(productData) {
    const container = document.getElementById('productSalesChart3D');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    // OrbitControlsの追加
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.minDistance = 5;
    controls.maxDistance = 20;
    controls.maxPolarAngle = Math.PI / 2;

    renderer.setClearColor(0xffffff);
    camera.position.set(0, 5, 5);
    camera.lookAt(0, 0, 0);

    let startAngle = 0;
    const EXTRUDE_DEPTH = 0.5; // 円グラフの厚み

    // 色を動的に生成する関数
    function getDynamicColor(index) {
        const baseColors = [0x00704A, 0x1E3932, 0x2D7F62, 0x4B9B7F];
        return baseColors[index % baseColors.length];
    }

    // データを正規化
    function normalizeData(data) {
        const total = data.reduce((sum, value) => sum + value, 0);
        return data.map(value => (value / total) * 100);
    }

    const normalizedData = normalizeData(productData.data);

    // 各セクターを作成
    normalizedData.forEach((percentage, index) => {
        const angle = (percentage / 100) * Math.PI * 2;

        // 扇形の形状を定義
        const shape = new THREE.Shape();
        shape.moveTo(0, 0);
        shape.arc(0, 0, 2, startAngle, startAngle + angle, false);
        shape.lineTo(0, 0);

        // 押し出し設定
        const extrudeSettings = {
            steps: 1,
            depth: EXTRUDE_DEPTH,
            bevelEnabled: true,
            bevelThickness: 0.1,
            bevelSize: 0.1,
            bevelOffset: 0,
            bevelSegments: 5
        };

        // ExtrudeGeometryを使用して立体的な形状を作成
        const geometry = new THREE.ExtrudeGeometry(shape, extrudeSettings);
        const material = new THREE.MeshPhongMaterial({
            color: getDynamicColor(index),
            side: THREE.DoubleSide,
            specular: 0x050505,
            shininess: 100
        });
        const sector = new THREE.Mesh(geometry, material);

        // セクターを水平に配置
        sector.rotation.x = -Math.PI / 2;
        scene.add(sector);

        // 商品名ラベルを追加
        const labelCanvas = document.createElement('canvas');
        labelCanvas.width = 256;
        labelCanvas.height = 64;
        const labelContext = labelCanvas.getContext('2d');
        labelContext.fillStyle = 'black';
        labelContext.font = '24px Arial';
        labelContext.textAlign = 'center';
        labelContext.fillText(`${productData.labels[index]} (${percentage.toFixed(1)}%)`, 128, 32);
        const texture = new THREE.CanvasTexture(labelCanvas);
        const labelMaterial = new THREE.SpriteMaterial({ map: texture });
        const labelSprite = new THREE.Sprite(labelMaterial);

        // ラベルの位置を設定
        const radius = 3;
        const labelAngle = startAngle + (angle / 2);
        labelSprite.position.set(
            radius * Math.cos(labelAngle),
            EXTRUDE_DEPTH + 0.2,
            radius * Math.sin(labelAngle)
        );
        labelSprite.scale.set(1.5, 0.4, 1);
        scene.add(labelSprite);

        startAngle += angle;
    });

    // 光源設定
    const light = new THREE.DirectionalLight(0xffffff, 1);
    light.position.set(5, 5, 5);
    scene.add(light);

    const bottomLight = new THREE.DirectionalLight(0xffffff, 0.5);
    bottomLight.position.set(-5, -5, -5);
    scene.add(bottomLight);

    scene.add(new THREE.AmbientLight(0x404040));

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    animate();
}

/**
 * 時間帯別注文数の3D棒グラフを初期化する
 * @param {Object} timeData - 時間帯別注文データ
 * @param {Array<number>} timeData.data - 各時間帯の注文数
 * @param {Array<string>} timeData.labels - 各時間帯のラベル（例: "7時", "8時"）
 */
function initializeTimeChart3D(timeData) {
    const container = document.getElementById('orderTimeChart3D');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    // OrbitControlsの追加
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true; // スムーズなアニメーション
    controls.dampingFactor = 0.05;
    controls.minDistance = 5; // 最小ズーム距離
    controls.maxDistance = 20; // 最大ズーム距離
    controls.maxPolarAngle = Math.PI / 2; // 地面より下にカメラが行かないように制限

    renderer.setClearColor(0xffffff);
    camera.position.set(5, 5, 10);
    camera.lookAt(0, 0, 0);

    const gridHelper = new THREE.GridHelper(10, 10);
    scene.add(gridHelper);

    const maxOrders = Math.max(...timeData.data); // 最大注文数を取得
    const minHeight = 0.5; // 最低高さを設定（見やすさ向上）

    timeData.data.forEach((value, index) => {
        const height = (value / maxOrders) * 5 + minHeight; // データ値に基づき高さをスケール変換
        const geometry = new THREE.BoxGeometry(0.5, height, 0.5);
        const material = new THREE.MeshPhongMaterial({ color: 0x00704A });
        const bar = new THREE.Mesh(geometry, material);

        // 棒の位置を設定
        bar.position.x = index - (timeData.data.length / 2);
        bar.position.y = height / 2;
        scene.add(bar);

        // 時間帯ラベルを追加
        const loader = new THREE.FontLoader();
        loader.load(
            'https://threejs.org/examples/fonts/helvetiker_regular.typeface.json',
            function (font) {
                const textGeometry = new THREE.TextGeometry(timeData.labels[index], {
                    font: font,
                    size: 0.2,
                    height: 0.05
                });
                const textMaterial = new THREE.MeshBasicMaterial({ color: 0x000000 });
                const text = new THREE.Mesh(textGeometry, textMaterial);
                text.position.set(bar.position.x, -0.5, 0);
                scene.add(text);
            }
        );
    });

    // 光源設定
    const light = new THREE.DirectionalLight(0xffffff, 1);
    light.position.set(5, 5, 5);
    scene.add(light);
    scene.add(new THREE.AmbientLight(0x404040));

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    animate();
}

/**
 * 注文ステータス内訳の3D円グラフを初期化する
 * @param {Object} statusData - ステータス別注文データ
 * @param {Array<number>} statusData.data - 各ステータスの割合（%）
 * @param {Array<string>} statusData.labels - 各ステータスのラベル
 */
function initializeStatusChart3D(statusData) {
    const container = document.getElementById('orderStatusChart3D');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.minDistance = 5;
    controls.maxDistance = 20;
    controls.maxPolarAngle = Math.PI / 2;

    renderer.setClearColor(0xffffff);
    camera.position.set(0, 5, 5);
    camera.lookAt(0, 0, 0);

    let startAngle = 0;
    const EXTRUDE_DEPTH = 0.5;
    const colors = [0x00704A, 0x1E3932, 0x2D7F62, 0x4B9B7F];

    function normalizeData(data) {
        const total = data.reduce((sum, value) => sum + value, 0);
        return data.map(value => (value / total) * 100);
    }

    const normalizedData = normalizeData(statusData.data);

    normalizedData.forEach((percentage, index) => {
        const angle = (percentage / 100) * Math.PI * 2;

        const shape = new THREE.Shape();
        shape.moveTo(0, 0);
        shape.arc(0, 0, 2, startAngle, startAngle + angle, false);
        shape.lineTo(0, 0);

        const extrudeSettings = {
            steps: 1,
            depth: EXTRUDE_DEPTH,
            bevelEnabled: true,
            bevelThickness: 0.1,
            bevelSize: 0.1,
            bevelOffset: 0,
            bevelSegments: 5
        };

        const geometry = new THREE.ExtrudeGeometry(shape, extrudeSettings);
        const material = new THREE.MeshPhongMaterial({
            color: colors[index % colors.length],
            side: THREE.DoubleSide,
            specular: 0x050505,
            shininess: 100
        });
        const sector = new THREE.Mesh(geometry, material);
        sector.rotation.x = -Math.PI / 2;
        scene.add(sector);

        const labelCanvas = document.createElement('canvas');
        labelCanvas.width = 256;
        labelCanvas.height = 64;
        const labelContext = labelCanvas.getContext('2d');
        labelContext.fillStyle = 'black';
        labelContext.font = '24px Arial';
        labelContext.textAlign = 'center';
        labelContext.fillText(`${statusData.labels[index]} (${percentage.toFixed(1)}%)`, 128, 32);
        const texture = new THREE.CanvasTexture(labelCanvas);
        const labelMaterial = new THREE.SpriteMaterial({ map: texture });
        const labelSprite = new THREE.Sprite(labelMaterial);

        const radius = 3;
        const labelAngle = startAngle + (angle / 2);
        labelSprite.position.set(
            radius * Math.cos(labelAngle),
            EXTRUDE_DEPTH + 0.2,
            radius * Math.sin(labelAngle)
        );
        labelSprite.scale.set(1.5, 0.4, 1);
        scene.add(labelSprite);

        startAngle += angle;
    });

    const light = new THREE.DirectionalLight(0xffffff, 1);
    light.position.set(5, 5, 5);
    scene.add(light);

    const bottomLight = new THREE.DirectionalLight(0xffffff, 0.5);
    bottomLight.position.set(-5, -5, -5);
    scene.add(bottomLight);

    scene.add(new THREE.AmbientLight(0x404040));

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }

    animate();
}

/**
 * ウィンドウリサイズ時にグラフのサイズを調整
 * - コンテナのサイズに合わせてcanvasをリサイズ
 * - カメラのアスペクト比を更新
 */
window.addEventListener('resize', () => {
    const containers = [
        'salesChart3D',
        'productSalesChart3D',
        'orderTimeChart3D',
        'orderStatusChart3D'
    ];

    containers.forEach(containerId => {
        const container = document.getElementById(containerId);
        const renderer = container.querySelector('canvas');
        // アスペクト比を更新
        const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);

        // レンダラーのサイズをコンテナに合わせる
        renderer.style.width = container.clientWidth + 'px';
        renderer.style.height = container.clientHeight + 'px';
    });
});
