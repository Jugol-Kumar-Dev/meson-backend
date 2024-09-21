<template>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="w-100 d-flex justify-content-between align-items-center pb-2">
                    <p class="fw-bolder p-0 fs-5">Mock-Tests</p>
                    <button class="btn btn-icon btn-circle btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#addMocktest"
                    >
                        <vue-feather type="plus" size="15"/>
                    </button>
                </div>

                <div class="border-top py-2 content-scrollbar">
                    <ul class="ps-0 course-list-group d-flex flex-column gap-2">
                        <li class="border-bottom pb-2 course-list-item" v-for="mocktest in  props.apiMocktests">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column gap-1 align-items-start">
                                    <div>
                                        <p class="m-0 p-0 fs-4 fw-bolder">{{ mocktest.name }}</p>
                                    </div>
                                    <div>
                                        <span v-if="parseInt(mocktest.status) === 1"
                                              class="badge badge-light-primary">Active</span>
                                        <span v-else class="badge badge-light-warning">Draft</span>
                                    </div>
                                </div>
                                <button class="btn btn-icon btn-circle btn-sm btn-danger" @click="deleteItem(`${props.deleteMockUrl}/?mock_id=${mocktest.id}&course_id=${props.course.id}`,null)">
                                    <vue-feather type="trash" size="15"/>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <Modal id="addMocktest" title="Add Mock-test In Course" v-vb-is:modal>
        <form @submit.prevent="saveMocktest">
            <div class="modal-body">
                <label>Mocktests: <small>(One Mocktest One Time)</small></label><br>
                <div class="mb-1">
                    <v-select v-model="updateForm.mock_id"
                              label="name"
                              placeholder="Select Mocktest For Assign"
                              :options="props.mocktests"
                              :reduce="mock => mock.id"
                    ></v-select>
                    <small class="text-danger" v-if="props.errors?.mock_id">{{ errors?.mock_id }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button :disabled="processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>

</template>

<script setup>

import {useAction} from "@/Composable/useAction.js";
import Modal from "@/Components/Modal.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    apiMocktests: Object,
    course:Object,
    mocktests:Array,
    deleteMockUrl: String,
    saveMocktest: String,
    errors: Object
})
// mock test sections
let updateForm = useForm({
    mock_id: null,
    course_id: props.course.id
})
const {deleteItem} = useAction();
const processing = ref(false)
let saveMocktest = () => {
    updateForm.put(props.saveMocktest, {
        onSuccess: () => {
            document.getElementById('addMocktest').$vb.modal.hide()
            updateForm.reset()
        }
    });
}


</script>

<style>

table td,
th {
    padding: 0.5rem;
}

#webviewer {
    height: 400px;
    width: 100%;
}

.jss339 {
    display: none !important;
}

.course-list-group {
    list-style: none;
}

.course-list-group li:last-child {
    border-bottom: none !important;
}

.last-video-border:last-child {
    border-bottom: none !important;
}

.hover-button-style:hover .action-btn-style {
    display: flex !important;
}

.content-scrollbar{
    max-height:800px;
    overflow-y: scroll;
}
.content-scrollbar::-webkit-scrollbar{
    display: none;
}
</style>
