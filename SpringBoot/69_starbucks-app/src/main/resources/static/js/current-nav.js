// 現在のURLのパスを取得して対応するリンクを強調
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/admin/dashboard')) {
        document.getElementById('dashboard-link')?.classList.add('bg-gray-800');
    } else if (currentPath.includes('/admin/products')) {
        document.getElementById('products-link')?.classList.add('bg-gray-800');
    } else if (currentPath.includes('/admin/options')) {
        document.getElementById('options-link')?.classList.add('bg-gray-800');
    }
});
