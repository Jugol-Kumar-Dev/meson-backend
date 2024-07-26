<template>

    <Layout>
        <Head title="coupon List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">coupon List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
    <!--                <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal"-->
    <!--                        data-bs-target="#createNewcoupon"><span>Add New coupon</span></button>-->
                    <button class="dt-button add-new btn btn-primary" @click="openAddForm"><span>Add New coupon</span></button>
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
                    <table class="user-list-table table">
                        <thead class="table-light">
                        <tr class="">
                            <th class="sorting">#_SL</th>
                            <th class="sorting">Name</th>
                            <th class="sorting">Code</th>
                            <th class="sorting">Type</th>
                            <th class="sorting">Amount</th>
                            <th class="sorting">Limit</th>
                            <th class="sorting">Valid Date</th>
                            <th class="sorting">Status</th>
                            <th class="sorting">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="coupon in coupons.data" :key="coupon.id">
                            <td>{{ coupon.id }}</td>
                            <td>{{ coupon.name }}</td>
                            <td>{{ coupon.code }}</td>
                            <td>
                                <span v-if="coupon.type === 'flat'" class="badge badge-light-primary">Flat</span>
                                <span v-else class="badge badge-light-info">Percentage</span>
                            </td>
                            <td>{{ coupon.value }} {{ coupon.type === 'flat' ? "TK" : "%" }}</td>
                            <td>{{ coupon.limit }}</td>
                            <td>{{ coupon.validate }}</td>
                            <td>
                                <span v-if="coupon.status == '1'" class="badge badge-light-success">Active</span>
                                <span v-else class="badge badge-light-warning">Inactive</span>
                            </td>

                            <td>

                                <div class="btn-group dropup dropdown-icon-wrapper">
                                    <button type="button"
                                            class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>

                                    <div class="dropdown-menu">
                                        <span class="dropdown-item"
                                              v-if="coupon.status == '1'"
                                              @click="avticationStatus(coupon.id, false)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                            <span class="ms-1">Inactive</span>
                                        </span>
                                        <span class="dropdown-item"
                                              v-else
                                              @click="avticationStatus(coupon.id, true)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                            <span class="ms-1">Active</span>
                                        </span>

                                        <span class="dropdown-item"
                                              @click="showItem(coupon.id)">
                                            <Icon title="eye"/>
                                           <span class="ms-1">Show</span>
                                        </span>
                                        <span class="dropdown-item"
                                              @click="deleteItemModal(coupon.id)">
                                            <Icon title="trash"/>
                                           <span class="ms-1">Delete</span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <Pagination :links="coupons.links" :from="coupons.from" :to="coupons.to"
                                :total="coupons.total"/>
                </div>
                <!-- Modal to add new user starts-->
                <Modal id="createNewcoupon" title="Create New coupon" v-vb-is:modal>
                    <form @submit.prevent="createNewcoupon">
                        <div class="modal-body">
                            <Text v-model="createForm.name" label="Coupon Name" placeholder="Coupon Name"/>
                            <Text v-model="createForm.code" label="Coupon Code" placeholder="Coupon Code"/>
                            <div class="row">
                                <div class="col-6">
                                    <Text v-model="createForm.price" label="Coupon Amount" placeholder="Coupon Amount"/>
                                </div>
                                <div class="col-6">
                                    <Text v-model="createForm.limit" label="Coupon Using Limit" placeholder="Max Using Limit"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label>Types: </label>
                                    <v-select v-model="createForm.type" label="name" :options="typs" :reduce="type => type.name"></v-select>
                                </div>
                                <div class="col-6">
                                    <label>Valid Date: </label>
                                    <datepicker v-model="createForm.validate" class="form-control" placeholder="Choose a date" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button :disabled="createForm.processing" type="submit"
                                    class="btn btn-primary waves-effect waves-float waves-light">Submit
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">Cancel
                            </button>
                        </div>
                    </form>
                </Modal>
                <!-- Modal to add new user Ends-->
            </div>
            <!-- list and filter end -->
        </section>
        <!-- Modal to edit coupon-->
        <Modal id="updatecoupon" title="Create New coupon" v-vb-is:modal>
            <form @submit.prevent="updateNewcoupon(editData.id)">
                <div class="modal-body">
                    <Text v-model="updateForm.name" label="coupon Name" placeholder="coupon Name"/>
                    <Image v-model="updateForm.photo" label="coupon Image"/>
                </div>
                <div class="modal-footer">
                    <button :disabled="createForm.processing" type="submit"
                            class="btn btn-primary waves-effect waves-float waves-light">Submit
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel
                    </button>
                </div>
            </form>
        </Modal>
    </Layout>
</template>

<script setup>
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import Icon from '@/Components/Icon.vue';
import Text from '@/Components/form/Text.vue';
import Image from '@/Components/form/Image.vue';
import Datepicker from 'vue3-datepicker'
import axios from 'axios';
import {ref, watch, computed} from 'vue';
import debounce from "lodash/debounce";
import Swal from 'sweetalert2'
import {router, useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

    let props = defineProps({
        coupons: Object,
        filters: Object,
        url: String,
    });

    let typs = [
        { "name" : 'flat'},
        { "name" : 'percent'},
    ]

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
                router.delete(props.url+"/" + id, {
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
    let createForm = useForm({
        name: '',
        code: '',
        price: '',
        type:'',
        validate:'',
        limit: '',
    });

    let updateForm = useForm({
        name: '',
        photo: null,
    })
    let makeid = (length) => {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789~!@#$%^&*(){}[]';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }


    let openAddForm = () =>{
        createForm.code = makeid(15);
        document.getElementById('createNewcoupon').$vb.modal.show()

    }

    let createNewcoupon = () => {
        createForm.post(props.url, {
            onSuccess: () => {
                document.getElementById('createNewcoupon').$vb.modal.hide()
                createForm.reset()
            }
        });
    }

    let editData = ref({});

    let editcoupon = (url) => {
        axios.get(url).then(res => {
            editData.value = res.data
            updateForm.name = res.data.name
            updateForm.photo = res.data.photo
            document.getElementById('updatecoupon').$vb.modal.show()
            console.log(res.data)
        }).catch(err => {
            console.log(err.data)
        })
    }

    let updateNewcoupon = (id) =>{
        console.log(updateForm);
        updateForm.post('/update-cat/'+id, {
            onSuccess: ()=>{
                document.getElementById('updatecoupon').$vb.modal.hide()
                updateForm.reset()
            }
        })
    }

    let avticationStatus = (value, status) => {
        console.log(value);
        Inertia.post(`/panel/coupon/activation/${value}?show_status=${status}`, {
            onSuccess: () => {
                //
            }
        })
    }


    let search = ref(props.filters.search);
    let perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        Inertia.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>
