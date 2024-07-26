<template>
    <div class="main-menu menu-fixed menu-accordion menu-shadow"
    :class="[
      { 'expanded': !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered) },
      themeSkin === 'light'|| themeSkin === 'bordered' ? 'menu-light' : 'menu-dark'
    ]"
    @mouseenter="updateMouseHovered(true)"
    @mouseleave="updateMouseHovered(false)"
    >
        <div class="navbar-header expanded">
            <slot
                name="header"
                :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :toggleCollapsed="toggleCollapsed"
                :collapseTogglerIcon="collapseTogglerIcon"
            >
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item me-auto" style="max-width: 70%;">
                        <img src="@/Images/logo2.png" alt="" style="width:100%">
                    </li>

                    <li class="nav-item nav-toggle">
                        <div class="nav-link modern-nav-toggle cursor-pointer">
                            <Icon title="x" width="24" height="24" @click="toggleVerticalMenuActive" class="d-block d-lg-none" />
                            <Icon :title="collapseTogglerIconFeather" width="24" height="24" @click="toggleCollapsed"
                                  class="d-none d-xl-block collapse-toggle-icon" />
                        </div>
                    </li>
                </ul>
            </slot>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content scroll-area">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/dashboard">
                        <Icon title="home" width="24" height="24" />
                        <span class="menu-title text-truncate">Dashboard</span>
                    </Link>
                </li>

<!--                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/student/chats">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span class="menu-title text-truncate">Chat</span>
                    </Link>
                </li>-->

                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/courses">
                        <Icon title="book" width="24" height="24" />
                        <span class="menu-title text-truncate">My Courses</span>
                    </Link>
                </li>
<!--                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/mocktest">
                        <Icon title="pencil" width="24" height="24" />
                        <span class="menu-title text-truncate">Mock Test</span>
                    </Link>
                </li>-->
<!--                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/wishlists">
                        <Icon title="heart" width="24" height="24" />
                        <span class="menu-title text-truncate">Wishlist</span>
                    </Link>
                </li>-->
                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/purchase">
                        <Icon title="dollar-sign" width="24" height="24" />
                        <span class="menu-title text-truncate">Purchase History</span>
                    </Link>
                </li>
<!--                <li class="nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/apply">
                        <Icon title="user-plus" width="24" height="24" />
                        <span class="menu-title text-truncate">Become an Instructor</span>
                    </Link>
                </li>-->
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import Icon from '@/Components/Icon.vue';

const props = defineProps({
  isVerticalMenuActive: {
      type: Boolean,
      required: true,
    },
    toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
})
const isMouseHovered = ref(false)
const openClass = ref('')
const clickMenu = ref('')
const store = useStore()

const themeSkin = computed(() => store.state.skin)
const isVerticalMenuCollapsed = computed(() => store.state.isVerticalMenuCollapsed)

const collapseTogglerIconFeather = computed(() => (collapseTogglerIcon.value === 'unpinned' ? 'circle' : 'disc'))

onMounted(() => store.commit('UDATE_SKIN', themeSkin.value))

const collapseTogglerIcon = computed(() => {
    if (props.isVerticalMenuActive) {
      return isVerticalMenuCollapsed.value ? 'unpinned' : 'pinned'
    }
    return 'close'
  })

  const toggleCollapsed = () => {
      store.commit('UPDATE_MENU_COLLAPSED', !isVerticalMenuCollapsed.value)
  }

  const toggleSubMenu = (val) => {
      openClass.value = openClass.value ? '' : 'open'
      clickMenu.value = clickMenu.value === val ? '' : val
  }

  const updateMouseHovered = val => {
    isMouseHovered.value = val
  }
</script>
