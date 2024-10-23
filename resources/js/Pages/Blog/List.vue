<template>
    <Layout>
        <Head title="Course List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Blogs List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <Link :href="props.url+'/create'" class="dt-button add-new btn btn-primary">
                        <span>Add New Blog</span>
                    </Link>
                </div>
            </div>
        </div>
        <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card pt-0">
                <div class="d-flex justify-content-between align-items-center header-actions mx-0 row mt-75">
                    <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
                        <div class="select-search-area">
                            <label>Show <select class="form-select" v-model="perPage">
                                <option :value="undefined">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-8 ps-xl-75 ps-0">
                        <div
                            class="d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap">
                            <div class="select-search-area">
                                <label>Search:<input v-model="search" type="search" class="form-control"
                                                     placeholder="Type here for search"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- list and filter end -->
        <div class="row match-height">
            <div v-for="blog in blogs.data" :key="blog.id" class="col-sm-12 col-md-6 col-lg-4">
                <div class="card mb-2 shadow-lg">
                    <div class="rounded-circle waves-effect position-absolute offset-right-0 position_size">
                        <button type="button" class="action_button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-more-vertical">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            <Link :href="blog.edit_url"  class="dropdown-item">
                                <Icon title="pencil"/>
                                <span class="ms-1">Edit</span>
                            </Link>
                            <span class="dropdown-item" @click="deleteItemModal(blog.id)">
                                <Icon title="trash"/>
                               <span class="ms-1">Delete</span>
                            </span>
                        </div>
                    </div>


                    <a href="javascript:void(0)" class="card-img-top">
                        <!-- Img  -->
                        <img :src="blog.cover" class="card-img-top rounded-top-md" alt=""></a>
                    <!-- Card body -->
                    <div class="card-body">
                        <div>
                            <span v-show="blog.publication_status"  class="badge badge-light-primary me-1"><Icon title="check"/></span>
                            <span v-if="blog.is_featured" class="badge badge-light-danger"><Icon title="heart"/></span>
                        </div>
                        <a href="#" class="fs-5 mb-1 fw-semi-bold"
                           :class="color[Math.floor(Math.random() * color.length)]">
                            {{ blog.category.name }}
                        </a>
                        <h3>
                            <a href="javascript:void(0)" class="text-inherit">{{ blog.title }}</a>
                        </h3>
                        <p v-html="blog.description"></p>
                        <!-- Media content -->
                        <div class="row align-items-center g-0 mt-1">
                            <div class="col-auto">
                                <div class="avatar avatar-lg">
                                    <img :src="blog.user.photo_url" :alt="blog.user.name">
                                </div>
                            </div>
                            <div class="col ms-1">
                                <h5 class="fw-bold">{{ blog.user.name }}</h5>
                                <p class="fs-6 mb-0">{{ blog.created_at }}</p>
                            </div>
                            <div class="col-auto">
                                <p class="fs-6 mb-0">{{ blog.viewers }} Read</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-0">
            <div class="card-body p-0">
                <Pagination :links="blogs.links" :from="blogs.from" :to="blogs.to" :total="blogs.total"/>
            </div>
        </div>

    </section>
    </Layout>
</template>

<script setup>
    import Pagination from '@/Components/Pagination.vue';
import Icon from '@/Components/Icon.vue';
import {ref, watch, computed} from 'vue';
import debounce from "lodash/debounce";
import Swal from "sweetalert2";
    import Layout from "@/Shared/Layout.vue";
    import {router} from "@inertiajs/vue3";

    let props = defineProps({
        blogs: Object,
        filters: Object,
        url: String,
    });
    let color = [
        'text-success',
        'text-danger',
        'text-primary',
        'text-secondary',
        'text-warning',
        'text-info'
    ]

    let deleteItemModal = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7121f5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(props.url + "/" + id, {
                    preserveState: true, replace: true, onSuccess: page => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    },
                    onError: errors => {
                        Swal.fire(
                            'Oops...',
                            'Something went wrong!',
                            'error'
                        )
                    }
                })
            }
        })
    };

    let search = ref(props.filters.search);
    let perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>

<style scoped>
    .action_button{
        border-radius: 100%;
        height: 34px;
        background: white;
        position: absolute;
        border:1px solid #f1f1f1;
        color: #6e6e6e;
    }
    .position_size{
        right: 22px;
        top: -14px;
    }
</style>
