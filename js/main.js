const TB = 560;
const PC = 960;

Vue.component('fade-in', {
  template: `
	<div class="fadein-box__outer">
  	<transition name="fadein">
      <div v-if="visible" class="fadein-box__inner">
  		<slot></slot>
      </div>
    </transition>
  </div>`,
  data() {
    return {
      visible: false
    };
  }, mounted() {
      if (!this.visible) {
        var top = this.$el.getBoundingClientRect().top;
        this.visible = top < window.innerHeight - 90;
      }    
  },
  methods: {
    handleScroll() {
      if (!this.visible) {
        var top = this.$el.getBoundingClientRect().top;
        this.visible = top < window.innerHeight - 90;
      }
    }
  },
  created() {
    window.addEventListener('scroll', this.handleScroll);
  },
  destroyed() {
    window.removeEventListener('scroll', this.handleScroll);
  }
})

new Vue({
  el: '#app',
  data: {
    scrollY: 0,
    isLoad: false,
    isDown: false,
    isMenu: false,
    isLeadEnd: false,
    isTopPage: false,
    isWorksPage: false,
    isBlogPage: false,
    isExhibitionPage: false,
    isAboutPage: false,
    isContactPage: false,
    isBreadcrumbDown: false,
    breadcrumbHeight: 24,
    isTb: false,
    isPC: false
  }, 
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
    window.addEventListener('resize', this.handleResize);
    if (window.innerWidth >= PC) {
      this.isMenu = true;
      this.isPC = true;
      this.isTb = false;
    } else if (window.innerWidth >= TB) {
      this.isMenu = false;
      this.isPC = false;
      this.isTb = true;
    } else {
      this.isMenu = false;
      this.isPC = false;
      this.isTb = false;
    }
    this.isLoad = true;
    if (location.pathname == '/') {
      this.isTopPage = true;
      if (window.pageYOffset == 0) {
        this.isDown = false;
      } else {
        this.isDown = true;     
      }
    } else {
      this.isTop = false;
      this.isDown = true;
      this.isTopPage = false;
      if (location.pathname.substr(0,6) == '/Works' || location.pathname.substr(0,6) == '/works' ) {
        this.isWorksPage = true;
      } else if (location.pathname.substr(0,5) == '/Blog' || location.pathname.substr(0,5) == '/blog' ) {
        this.isBlogPage = true;
      } else if (location.pathname.substr(0,11) == '/Exhibition' || location.pathname.substr(0,11) == '/exhibition' ) {
        this.isExhibitionPage = true;
      } else if (location.pathname.substr(0,6) == '/About' || location.pathname.substr(0,6) == '/about' ) {
        this.isAboutPage = true;
      } else if (location.pathname.substr(0,8) == '/Contact' || location.pathname.substr(0,8) == '/contact' ) {
        this.isContactPage = true;
      }      
    }
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    handleScroll() {
      // トップから少しスクロールした時
      if(this.isTopPage){
        (window.scrollY > 100 ) ? this.isDown = true : this.isDown = false ;
      } else {
        (window.scrollY > this.breadcrumbHeight ) ? this.isBreadcrumbDown = true : this.isBreadcrumbDown = false ;
      }
    },
    handleResize() {
      if (window.innerWidth >= PC) {
        this.isMenu = true;
        this.isPC = true;
        this.isTb = false;
      } else if (window.innerWidth >= TB) {
        this.isMenu = false;
        this.isPC = false;
        this.isTb = true;
      } else {
        this.isMenu = false;
        this.isPC = false;
        this.isTb = false;
      }
    }
  }
})