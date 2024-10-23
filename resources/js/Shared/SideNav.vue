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
                        <div class="nav-link modern-nav-toggle">
                            <Icon title="x" width="24" height="24" @click="toggleVerticalMenuActive"
                                  class="d-block d-lg-none"/>
                            <Icon :title="collapseTogglerIconFeather" width="24" height="24" @click="toggleCollapsed"
                                  class="d-none d-xl-block collapse-toggle-icon"/>
                        </div>
                    </li>
                </ul>
            </slot>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content scroll-area">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item" v-if="$page.props.auth.user.role === 'instructor'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/dashboard">
                        <Icon title="home" width="24" height="24"/>
                        <span class="menu-title text-truncate">Dashboard</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/dashboard">
                        <Icon title="home" width="24" height="24"/>
                        <span class="menu-title text-truncate">Dashboard</span>
                    </Link>
                </li>
                <li class="nav-item has-sub" :class="{'open' : clickMenu === 'Courses'}"
                    @click="toggleSubMenu('Courses')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <Icon title="book"/>
                        <span class="menu-title text-truncate">Course Tools</span>
                    </a>

                    <ul class="menu-content">
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/categories">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-item text-truncate">Categories</span>
                            </Link>
                        </li>
                        <!--                        <li>
                                                    <Link preserve-scroll class="d-flex align-items-center" href="/sub_categories">
                                                        <Icon title="circle" width="24" height="24" />
                                                        <span class="menu-item text-truncate">Sub Categories</span>
                                                    </Link>
                                                </li>
                                                <li>
                                                    <Link preserve-scroll class="d-flex align-items-center" href="/child_categories">
                                                        <Icon title="circle" width="24" height="24" />
                                                        <span class="menu-item text-truncate">Child Categories</span>
                                                    </Link>
                                                </li>-->
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/courses">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-title text-truncate">Courses</span>
                            </Link>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-sub" :class="{'open' : clickMenu === 'questions'}"
                    @click="toggleSubMenu('questions')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M11.07 12.85c.77-1.39 2.25-2.21 3.11-3.44c.91-1.29.4-3.7-2.18-3.7c-1.69 0-2.52 1.28-2.87 2.34L6.54 6.96C7.25 4.83 9.18 3 11.99 3c2.35 0 3.96 1.07 4.78 2.41c.7 1.15 1.11 3.3.03 4.9c-1.2 1.77-2.35 2.31-2.97 3.45c-.25.46-.35.76-.35 2.24h-2.89c-.01-.78-.13-2.05.48-3.15M14 20c0 1.1-.9 2-2 2s-2-.9-2-2s.9-2 2-2s2 .9 2 2"/>
                        </svg>
                        <span class="menu-title text-truncate">Exams</span>
                    </a>
                    <ul class="menu-content">
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/questions-categories">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-title text-truncate">Question Category</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/questions">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-title text-truncate">Questions</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/mocktests">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-title text-truncate">Exams</span>
                            </Link>
                        </li>
                    </ul>
                </li>


                <li v-if="$page.props.auth.user.role === 'admin'" class="nav-item has-sub"
                    :class="{'open' : clickMenu === 'Authentication'}" @click="toggleSubMenu('Authentication')">
                    <a preserve-scroll class="d-flex align-items-center" href="javascript:void(0)">
                        <Icon title="user-check"/>
                        <span class="menu-title text-truncate">Approval</span>
                    </a>
                    <ul class="menu-content">
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/students">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-item text-truncate">Student</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/admins">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-item text-truncate">Admin</span>
                            </Link>
                        </li>
                        <li>
                            <Link preserve-scroll class="d-flex align-items-center" href="/panel/instructors">
                                <Icon title="circle" width="24" height="24"/>
                                <span class="menu-item text-truncate">Instructor</span>
                            </Link>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/blogs">
                        <vue-feather type="codepen" width="24" height="24"/>
                        <span class="menu-title text-truncate">Blogs</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/review">
                        <vue-feather type="send" size="24"/>
                        <span class="menu-title text-truncate">Reviews</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/groups">
                        <vue-feather type="users" size="24"/>
                        <span class="menu-title text-truncate">Group</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/coupons">
                        <vue-feather type="percent" size="24"/>
                        <span class="menu-title text-truncate">Coupon</span>
                    </Link>
                </li>

                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/pages">
                        <vue-feather type="book-open" size="24"/>
                        <span class="menu-title text-truncate">Pages</span>
                    </Link>
                </li>

                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/transactions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M15.5 15.5a1 1 0 1 0 2 0a1 1 0 1 0-2 0"/><path d="M7 7a2 2 0 1 1 4 0v9a3 3 0 0 0 6 0v-.5M8 11h6"/></g></svg>
                        <span class="menu-title text-truncate">Transactions</span>
                    </Link>
                </li>

                <li class="nav-item" v-if="$page.props.auth.user.role === 'admin'">
                    <Link preserve-scroll class="d-flex align-items-center" href="/panel/settings">
                        <vue-feather type="settings"/>
                        <span class="menu-title text-truncate">Settings</span>
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue'
import {useStore} from 'vuex'
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
