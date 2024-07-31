<template>

    <Layout>

    <Head title="Course List"/>
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Course List</h2>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <Link :href="props.url+'/create'" class="dt-button add-new btn btn-primary">
                    <span>Add New Course</span>
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

        <div class="row row-cols-md-3">
            <div  v-for="course in courses.data" :key="course.id"  class="col">
                <div class="card rounded-md">
                    <div class="card-body">
                        <div class="course-card">
                            <div class="course-card__thumbnail">
                                <img :src="`${course.cover}`" @error="errorImage" class="w-100 object-fit-cover" style="max-height: 200px;">
                            </div>
                            <div class="course-card__body">
                                <Link :href="course.show_url" class="course-card__body-title">
                                    <h5 class="text-primary mt-2 text-capitalize">
                                        {{ course?.name?.slice(0, 45) }}...
                                    </h5>
                                </Link>
        <!--                        <small>{{ course?.description?.slice(0, 55) }}...</small>-->
                            </div>
                            <div class="course-card__footer d-flex align-items-center justify-content-between">
                                <div class="review">
                                    <strong>{{ course?.price }} {{ $page.props?.curency ?? 'BDT' }}</strong>
                                </div>
                                <div class="btn-group dropup dropdown-icon-wrapper">
                                    <button type="button"
                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        <Link :href="course.show_url" class="dropdown-item">
                                            <Icon title="eye"/>
                                           <span class="ms-1">Show</span>
                                        </Link>
                                        <Link :href="course.edit_url" class="dropdown-item">
                                            <Icon title="pencil"/>
                                           <span class="ms-1">Edit</span>
                                        </Link>
                                        <span class="dropdown-item"
                                              @click="deleteItemModal(course.id)">
                                            <Icon title="trash"/>
                                           <span class="ms-1">Delete</span>
                                        </span>
                                    </div>
                                </div>
        <!--                        <div class="d-flex align-items-center gap-1 mt-1">
                                    <Link :href="course.show_url" class="text-success">
                                        <Icon title="eye"/>
                                        <span> Show</span>
                                    </Link>
                                    <span>|</span>
                                    <Link :href="course.edit_url" type="button" class="text-primary">
                                        <Icon title="pencil"/>
                                        <span> Edit</span>
                                    </Link>
                                    <span>|</span>
                                    <button @click="deleteItemModal(course.id)" type="button"
                                            class="bg-white text-danger border-0">
                                        <Icon title="trash"/>
                                        <span> Delete</span>
                                    </button>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!--
                    <div v-for="course in courses.data" :key="course.id" class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card border-success mx-2">
                            <div class="bg-light-primary rounded-top text-center">
                                <img :src="course.cover"
                                     :alt="course.name" height="170"
                                     class="">
                            </div>
                            <div class="card-body px-2">
                                <div class="d-flex align-items-center">
                                    <div class="my-auto">
                                        <h4 class="card-title mb-25">{{ course.name }}</h4>
                                    </div>
                                </div>
                                <div class="mt-0">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex align-items-center">
                                            <span>Price:  {{ course.price }}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <span>Category: {{ course.category }}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <span>Active From: {{ course.active_on }}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <span>Status: {{ course.status }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-text pt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <Link :href="course.show_url" class="btn btn-outline-success waves-effect">
                                            <Icon title="eye"/>
                                            <span> Show</span>
                                        </Link>
                                        <Link :href="course.edit_url" type="button" class="btn btn-outline-primary waves-effect">
                                            <Icon title="pencil"/>
                                            <span> Edit</span>
                                        </Link>
                                        <button @click="deleteItemModal(course.id)" type="button"
                                                class="btn btn-outline-danger waves-effect">
                                            <Icon title="trash"/>
                                            <span> Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
        </div>


        <Pagination :links="courses.links" :from="courses.from" :to="courses.to" :total="courses.total"/>

        <!-- list and filter end -->
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
        courses: Object,
        filters: Object,
        url: String,
    });

    let deleteItemModal = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
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
