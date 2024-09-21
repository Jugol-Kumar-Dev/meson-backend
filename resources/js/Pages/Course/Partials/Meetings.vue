<script setup>
    import Modal from "@/Components/Modal.vue";
    import Text from "@/Components/form/Text.vue";
    import Datepicker from "vue3-datepicker";
    import Textarea from "@/Components/form/Textarea.vue";
    import {useForm} from "@inertiajs/vue3";
    import Image from "@/Components/form/Image.vue";
    import moment from "moment";

    const props = defineProps({
        courseId:Number,
        liveClass:Object,
        errors:Object,
    })
    // photo	user_id	course_id	title	start_time	link	agenda
    const createForm = useForm({
        course_id:props.courseId,
        photo:null,
        title:null,
        start_time:null,
        link:null,
        agenda:null,
        errors:null,
    })

    const submitForm = ()=>{
        createForm.post('/panel/live-class', {
            onSuccess:()=>{
                createForm.reset();
                document.getElementById('addZoom').$vb.modal.hide()
            }
        })
    }

</script>

<template>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="w-100 d-flex justify-content-between align-items-center pb-2">
                    <p class="fw-bolder fs-5 p-0">Live Classes</p>
                    <button class="btn btn-icon btn-circle btn-sm btn-primary"
                            type="button" data-bs-toggle="modal"
                            data-bs-target="#addZoom">
                        <vue-feather type="plus" size="15"/>
                    </button>
                </div>

                <div class="border-top py-2 content-scrollbar">
                    <ul class="ps-0 course-list-group d-flex flex-column gap-2">
                        <li class="border-bottom pb-2 course-list-item" v-for="item in liveClass">
                            <div class="d-flex align-items-start justify-content-between">
                                <div style="width:50px; height:auto">
                                    <img :src="item?.photo_url" class="w-100 h-auto object-fit-cover" alt="">
                                </div>
                                <div class="d-flex flex-column gap-1 align-items-start">
                                    <div>
                                        <p class="m-0 p-0 fs-4 fw-bolder">{{ item?.title }}</p>
                                        <p class="m-0 p-0">{{ moment(item?.start_time)?.format('lll')}}</p>
                                    </div>
                                    <span class="badge badge-light-primary">
                                        Active
                                    </span>
                                </div>
                                <button class="btn btn-icon btn-circle btn-sm btn-danger">
                                    <vue-feather type="trash" size="15"/>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <Modal id="addZoom" title="Add Live Class" v-vb-is:modal>
        <form @submit.prevent="submitForm">
            <div class="modal-body">
                <Image v-model="createForm.photo"/>
                <Text v-model="createForm.title"
                      type="text" label="Live Class Title"
                      :error="errors.title"
                      placeholder="Lesson title"/>
                <span class="text-danger text-small" v-if="errors?.course_id">{{ errors?.course_id }}</span>

                <lebel>Start Time</lebel>
                <datepicker v-model="createForm.start_time"
                            :minimumView="'time'"
                            inputFormat="yyyy-MM-dd HH:mm"
                            class="form-control mb-1"
                            placeholder="Start Time"/>
                <p class="text-danger text-small mb-1" v-if="errors?.start_time">{{ errors?.start_time }}</p>

                <Text v-model="createForm.link"
                      type="text" label="Live Class Link"
                      :error="errors.link"
                      placeholder="Lesson title"/>
                <lebel>Agenda</lebel>
                <Textarea v-model="createForm.agenda" placeholder="Class Agenda"></Textarea>
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
