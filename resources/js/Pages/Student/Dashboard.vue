<template>
    <Head title="Dashboard"/>
    <section class="app-dashboard bg-white">
        <div class="card bg-white">
            <div class="card-body border-bottom">
                <h4 class="card-title">Welcome Again To Dashboard</h4>
            </div>
        </div>
    </section>

    <section>
        <div class="row">
<!--            <div class="col-lg-3 col-sm-6">
                <Link href="/wishlists">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="fw-bolder mb-75">{{ report.total_wishlist_course }}</h3>
                                <span>Wishlists Courses</span>
                            </div>
                            <div class="avatar bg-light-warning p-50">
                                <span class="avatar-content">
                                    <Icon title="heart" width="24" height="24"/>
                                </span>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>-->

            <div class="col-lg-3 col-sm-6">
                <Link href="/courses">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="fw-bolder mb-75">{{ report.total_courses }}</h3>
                                <span>My Courses</span>
                            </div>
                            <div class="avatar bg-light-primary p-50">
                                <span class="avatar-content">
                                    <Icon title="book"  width="24" height="24"/>
                                </span>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
<!--            <div class="col-lg-3 col-sm-6">
                <a href="/purchase">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="fw-bolder mb-75">{{ report.total_transactions }}</h3>
                                <span>Total Perches</span>
                            </div>
                            <div class="avatar bg-light-danger p-50">
                            <span class="avatar-content">
                                <Icon title="dollar-sign"  width="24" height="24"/>
                            </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="/mocktest">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="fw-bolder mb-75">{{ report.total_mok }}</h3>
                                <span>Total Mocktest's</span>
                            </div>
                            <div class="avatar bg-light-success p-50">
                            <span class="avatar-content">
                                <Icon title="pencil"  width="24" height="24"/>
                            </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>-->
        </div>
    </section>


</template>

<script>
    import SLayout from './Layout.vue'
    import Icon from '@/Components/Icon.vue'

    export default {
        components:{
          Icon
        },
        layout: SLayout,
    };
</script>

<script setup>
    let prop = defineProps({
        report: Object,
    })
</script>
