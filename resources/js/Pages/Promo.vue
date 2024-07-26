<template>

    <Layout>
        <Head title="Promo List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Promo List</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <button class="dt-button add-new btn btn-primary" tabindex="0" type="button" data-bs-toggle="modal"
                            data-bs-target="#createNewCategory"><span>Add Promo</span></button>
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
                </div>
            </div>
            <!-- list and filter end -->


            <div class="row row-cols-md-3">
                <div  v-for="promo in promos.data" :key="promoid"  class="col">
                    <div class="card rounded-md">
                        <div class="card-body">
                            <div class="course-card">
                                <div v-html="promo?.video" />
                                <div class="course-card__body">
                                    <h5 class="text-primary mt-2">
                                        {{ promo?.name?.slice(0, 45) }}...
                                    </h5>
                                    <!--                        <small>{{ promo?.description?.slice(0, 55) }}...</small>-->
                                </div>
                                <div class="course-card__footer d-flex align-items-center justify-content-between">
                                    <div class="btn-group dropup dropdown-icon-wrapper">
                                        <button type="button"
                                                class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <span class="dropdown-item"
                                                  @click="deleteItemModal(promo?.id)">
                                                <Icon title="trash"/>
                                               <span class="ms-1">Delete</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--
                                    <div v-for="course in courses.data" :key="promoid" class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="card border-success mx-2">
                                            <div class="bg-light-primary rounded-top text-center">
                                                <img :src="promocover"
                                                     :alt="promoname" height="170"
                                                     class="">
                                            </div>
                                            <div class="card-body px-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="my-auto">
                                                        <h4 class="card-title mb-25">{{ promoname }}</h4>
                                                    </div>
                                                </div>
                                                <div class="mt-0">
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Price:  {{ promoprice }}</span>
                                                        </li>
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Category: {{ promocategory }}</span>
                                                        </li>
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Active From: {{ promoactive_on }}</span>
                                                        </li>
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <span>Status: {{ promostatus }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="card-text pt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <Link :href="promoshow_url" class="btn btn-outline-success waves-effect">
                                                            <Icon title="eye"/>
                                                            <span> Show</span>
                                                        </Link>
                                                        <Link :href="promoedit_url" type="button" class="btn btn-outline-primary waves-effect">
                                                            <Icon title="pencil"/>
                                                            <span> Edit</span>
                                                        </Link>
                                                        <button @click="deleteItemModal(promoid)" type="button"
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



            <Pagination :links="promos.links" :from="promos.from" :to="promos.to"
                        :total="promos.total"/>
        </section>

        <!-- Modal to add new user starts-->
        <Modal id="createNewCategory" title="Create New Promo" v-vb-is:modal>
            <form @submit.prevent="createNewCategory">
                <div class="modal-body">
                    <Text v-model="createForm.name" :error="props.errors.name" label=" Name" placeholder="Category Name"/>
<!--                    <Image v-model="createForm.image"  :error="props.errors.photo" label=" Image"/>-->
                    <textarea class="form-control" v-model="createForm.video" rows="5" placeholder="e.g video ifream link..."></textarea>
                    <span v-if="props.errors.video" class="error text-danger">{{ props.errors.video }}</span>
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
        <!--    show modal-->
        <Modal id="showItem" title="Show Category" v-vb-is:modal>
            <div class="modal-dialog modal-lg modal-dialog-centered d-flex flex-column">
                <img :src="showData.photo" :alt="showData.name" class="avatar" width="60" height="60">
                <h3 class="mt-2">{{ showData.name }}</h3>
            </div>
            <div class="modal-dialog-centered mx-auto mb-1">
                <button class="btn bg-light-primary me-2">Edit</button>
                <button class="btn bg-light-danger">Delete</button>
            </div>
        </Modal>
        <!-- Modal to edit category-->
        <Modal id="updateCategory" title="Create New Category" v-vb-is:modal>
            <form @submit.prevent="updateNewCategory(editData.id)">
                <div class="modal-body">
                    <Text v-model="updateForm.name" label="Category Name" placeholder="Category Name"/>
<!--                    <Image v-model="updateForm.image" label="Category Image"/>-->
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
import axios from 'axios';
import {ref, watch, computed} from 'vue';
import debounce from "lodash/debounce";
import Swal from 'sweetalert2'
import {router, useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

    let props = defineProps({
        promos: Object,
        filters: Object,
        url: String,
        errors:Object
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
                router.delete(props.url + '/'+id, {
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
        image: null,
        video:null,
    });

    let updateForm = useForm({
        name: '',
        image: null,
        video:null,
    })

    let createNewCategory = () => {
        // console.log(createForm);
        createForm.post(props.url, {
            onSuccess: () => {
                document.getElementById('createNewCategory').$vb.modal.hide()
                createForm.reset()
            }
        });
    }

    let editData = ref({});

    let editCategory = (url) => {
        axios.get(url).then(res => {
            editData.value = res.data
            updateForm.name = res.data.name
            updateForm.photo = res.data.photo
            document.getElementById('updateCategory').$vb.modal.show()
            console.log(res.data)
        }).catch(err => {
            console.log(err.data)
        })
    }

    let updateNewCategory = (id) =>{
        console.log(updateForm);
        updateForm.post('/update-cat/'+id, {
            onSuccess: ()=>{
                document.getElementById('updateCategory').$vb.modal.hide()
                updateForm.reset()
            }
        })
    }


    let showData = ref([])
    let showItem = (slug) => {
        axios.get('categories/' + slug).then(res => {
            showData.value = res.data;
            document.getElementById('showItem').$vb.modal.show()
        }).catch(err => {
            console.log(err)
        })
    }


    let search = ref(props.filters.search);
    let perPage = ref(props.filters.perPage);

    watch([search, perPage], debounce(function ([val, val2]) {
        router.get(props.url, {search: val, perPage: val2}, {preserveState: true, replace: true});
    }, 300));

</script>
