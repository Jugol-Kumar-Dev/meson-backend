<template>

    <Layout>
        <Head title="Mocktest List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Exam List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <Link :href="props.url+'/create'" class="dt-button add-new btn btn-primary">
                        <span>Add New Exam</span>
                    </Link>
                </div>
            </div>
        </div>
        <section class="app-user-list">
            <!-- list and filter start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
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
                    <table class="user-list-table table table-striped">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">Name</th>
                            <th class="sorting">Total Q</th>
                            <th class="sorting">Duration</th>
                            <th class="sorting">Question Type</th>
                            <th class="sorting">Status</th>
<!--                            <th class="sorting">Shoing Student Panel</th>-->
                            <th class="sorting">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="mocktest in mocktests.data" :key="mocktest.id">
                            <td>{{ mocktest.name }}</td>
                            <td>{{ mocktest.total_q ?? mocktest?.questionCount }}</td>
                            <td>
                                <div class="d-flex flex-column" v-if="mocktest.exam_type === 'main' && mocktest?.start_time && mocktest?.end_time">
                                    <span>Start Time: {{ moment(mocktest?.start_time)?.format('lll') }}</span>
                                    <span>End Time: {{ moment(mocktest?.end_time)?.format('lll') }}</span>
                                    <span>Duration: {{ mocktest.duration }} Min</span>
                                </div>
                                <div v-else>
                                    {{ mocktest.duration }} Min
                                </div>
                            </td>
                            <td><span class="fw-bold text-capitalize">{{ mocktest.exam_type === 'main' ? 'Live Exam' : 'Practice Exam' }}</span></td>
                            <td>
                                <span class="badge"
                                      :class="
                                      {
                                          'bg-light-primary' : mocktest.status == 1,
                                          'bg-light-warning' :   mocktest.status == 0,
                                      }"
                                >
                                {{ mocktest.status ? 'Active' : 'Inactive' }}
                            </span>
                            </td>
<!--                            <td class="text-center">
                                <span class="badge" :class="{
                                          'bg-light-info' : mocktest.student_show == 1,
                                          'bg-light-secondary' :  mocktest.student_show == 0,
                                      }">
                                    {{ mocktest.student_show ? 'YES' : 'NO' }}
                                </span>
                            </td>-->
                            <td>

                                <div class="btn-group dropup dropdown-icon-wrapper">
                                    <button type="button"
                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>

                                    <div class="dropdown-menu">
<!--                                        <span class="dropdown-item"
                                              v-if="mocktest.student_show"
                                              @click="updateforPublished(mocktest.id, false)">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-arrow-down">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <polyline points="19 12 12 19 5 12"></polyline>
                                           </svg>
                                           <span class="ms-1">Unpublished</span>
                                        </span>
                                        <span class="dropdown-item"
                                              v-else
                                              @click="updateforPublished(mocktest.id, true)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                                           <span class="ms-1">Published</span>
                                        </span>-->

                                        <span class="dropdown-item"
                                              v-if="mocktest.status"
                                              @click="avticationStatus(mocktest.id, false)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                            <span class="ms-1">Inactive</span>
                                        </span>
                                        <span class="dropdown-item"
                                              v-else
                                              @click="avticationStatus(mocktest.id, true)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                            <span class="ms-1">Active</span>
                                        </span>

                                        <Link :href="`/panel/mock-full-details/${mocktest.id}`" class="dropdown-item" >
                                            <Icon title="eye"/>
                                           <span class="ms-1">Show</span>
                                        </Link>

                                        <span class="dropdown-item"
                                              @click="deleteItemModal(mocktest.id)">
                                            <Icon title="trash"/>
                                           <span class="ms-1">Delete</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="mocktests.links" :from="mocktests.from" :to="mocktests.to"
                                :total="mocktests.total"/>
                </div>
            </div>
            <!-- list and filter end -->
        </section>
        <Modal id="showItem" size="md" title="Show Category" v-vb-is:modal>
        <div class="modal-content">
            <div class="modal-body pb-3 px-sm-3">
                <div>
                    <h1 class="text-center mb-1" id="createAppTitle">{{ mocktest.name }}</h1>
                    <h4>Qusations: {{ mocktest.total_q }}</h4>
                    <h4>Time: {{ mocktest.duration }}Min</h4>
                </div>

                <div class="bs-stepper vertical wizard-modern mt-5">
                    <small>Sub Category: </small>
                    <ul class="d-flex">
                        <li v-for="(item, index) in sub_categories" :key="item.id"
                            class="list-group-item p-25 border-0">
                            <span class="badge badge-light-primary">{{ item.name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </Modal>
    </Layout>

</template>

<script setup>
import Pagination from '@/Components/Pagination.vue';
import Icon from '@/Components/Icon.vue';
import Modal from '@/Components/Modal.vue'
import {ref, watch} from 'vue';
import debounce from "lodash/debounce";
import Swal from 'sweetalert2'
import axios from "axios";
import Layout from "@/Shared/Layout.vue";
import {router} from "@inertiajs/vue3";
import moment from "moment";
let props = defineProps({
    mocktests: Object,
    filters: Object,
    url: String,
});

let deleteItemModal = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6230d6',
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
                        errors[0],
                        'error'
                    )
                }
            })
        }
    })
};


let mocktest = ref([])
let category = ref([])
let sub_categories = ref([])
let child_category = ref([])

let showItem = (slug) => {
    axios.get(props.url + '/' + slug).then(res => {
        console.log(res);
        mocktest.value = res.data
        sub_categories.value = res.data.sub_categories
        document.getElementById('showItem').$vb.modal.show()
    }).catch(err => {
        console.log(err)
    })
}
let updateforPublished = (value, status) => {
    console.log(value);
    router.post(`/panel/mocktsts/published-for-student/${value}?show_status=${status}`, {
        onSuccess: () => {
            //
        }
    })
}

let avticationStatus = (value, status) => {
    console.log(value);
    router.post(`/panel/mocktsts/activation/${value}?show_status=${status}`, {
        onSuccess: () => {
            //
        }
    })
}


let search = ref(props.filters.search);
let perPage = ref(props.filters.perPage);

watch([search, perPage], debounce(function ([val, val2]) {
    router.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
}, 300));

</script>
