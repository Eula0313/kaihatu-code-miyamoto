new Vue({
  el:'#app',
  data() {
    return {
      // 商品一覧
      items: [],
      activeTab: 1, // アクティブなタブ
      // 絞り込みテキスト
      filterText: "",
      errorFlag: false,
      prices: [
        {
          id: 1, text: "～100円"
        
        },
        {
          id: 2, text: "101円～500円"
        
        },
        {
          
          id: 3, text: "501円～"
        }
      ],
      // 絞り込み価格ID
      filterPriceId: undefined
    }
  },
  computed: {
    // 商品名で絞り込むタブがアクティブ
    acitiveWordsTab() {
      return this.activeTab === 1;
    },
    // 価格で絞り込むタブがアクティブ
    acitivePriceTab () {
      return this.activeTab === 2;
    },
    // 商品名で絞り込み込みした商品一覧
    filteredItemsByText() {
      //コールバック関数でthisを参照できないため別変数へ代入
      const self = this
      if (this.filterText) {  // 絞り込みテキストが入力されていた場合
        return this.items.filter(function (item) {  //オブジェクト配列itemsから一つずつ取り出し変数itemへ代入（テキストP153　filterメソッド参照)
          return item.name.indexOf(self.filterText.trim()) !== -1 //item.nameにfilterTextが含まれる（含まれない場合は-1）場合のみreturn
        })
      } else {
        return this.items
      }
    },
    // 価格で絞り込み込みした商品一覧
    filteredItemsByPriceId () {
      //コールバック関数でthisを参照できないため別変数へ代入
      const self = this
      if (this.filterPriceId) { // 絞り込み価格IDが選択されていた場合
        return this.items.filter(function (item) {  //オブジェクト配列itemsから一つずつ取り出し変数itemへ代入（テキストP153　filterメソッド参照)
          switch (self.filterPriceId) { //item.priceが条件に合う場合のみreturn
            case 1:
              return item.price <= 100
              case 2:
                return item.price > 100 && item.price <= 500;
              case 3:
                return item.price > 500;
              default:
                return item.price >= 0;
            }
        });
      } else {
        return this.items
      }
    },
    // アクティブなタブの状態を判別して商品一覧を出し分ける
    filteredItems () {
      if (this.acitiveWordsTab) {// 商品名で絞り込むタブがアクティブ
        return this.filteredItemsByText
      } else if (this.acitivePriceTab) {// 価格で絞り込むタブがアクティブ
        return this.filteredItemsByPriceId
      } else {
        return this.items
      }
    }
  },
  methods: {
    // 商品一覧をjsonから取得する
    fetchItem () {
      //コールバック関数でthisを参照できないため別変数へ代入
      const self = this
      //axios.get("./item.json").then(function (response) {
      axios.get("./select_all.php").then(function (response) {
        self.items = response.data
      })
      .catch(function(error) {
        self.errorFlag = true;
        alert('ERROR!! 商品一覧が取得できませんでした')
      });
    },
    // タブを切り替えて検索条件を初期化する
    changeTab (number) {
      this.initialize()
      this.activeTab = number
    },
    // 検索条件を初期化する
    initialize () {
      this.filterText = ""
      this.filterPriceId = undefined
    }
  },
  mounted () {
    // アプリケーションが立ち上がったら商品一覧を取得する
    // インスタンス初期化時、DOMが生成された後に実行される
    this.fetchItem()
  }
})
