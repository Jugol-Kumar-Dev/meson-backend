<template>

    <Layout>
        <Head title="Slider List"/>

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Settings</h2>
                    </div>
                </div>
            </div>
        </div>







        <div class="row">
            <div class="col-md-3 d-flex flex-column gap-2">
                <div class="btn btn-outline-primary" :class="{ 'bg-primary text-white fw-bolder': currentTab === 'details' }"
                     @click="setActiveTab('details')">
                    Home Page Setting
                </div>

                <div class="btn btn-outline-primary" :class="{ 'bg-primary text-white fw-bolder': currentTab === 'homeMessage' }"
                     @click="setActiveTab('homeMessage')">
                        Home Message
                </div>

                <div class="btn btn-outline-primary" :class="{ 'bg-primary text-white fw-bolder': currentTab === 'contactUs' }"
                     @click="setActiveTab('contactUs')">
                        Contact Us
                </div>

                <div class="btn btn-outline-primary" :class="{ 'bg-primary text-white fw-bolder': currentTab === 'headerPages' }"
                     @click="setActiveTab('headerPages')">
                    Header Pages
                </div>

                <div class="btn btn-outline-primary" :class="{ 'bg-primary text-white fw-bolder': currentTab === 'footerPages' }"
                     @click="setActiveTab('footerPages')">
                    Footer Pages
                </div>

            </div>
            <div class="col-md-9">
                <div class="card" v-if="currentTab === 'details'">
                    <div class="card-header d-flex gap-2">
                        <h3 class="card-title m-0">Home page setting</h3>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body border-primary rounded-2">
                                            <h3 class="my-1">Section 1</h3>
                                            <Text v-model="createForm.s1Title"  label="Section Title"/>
                                            <Text v-model="createForm.s1Slogan"  label="Section Slogan"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body border-primary rounded-2">
                                            <h3 class="my-1">Section 2</h3>
                                            <Text v-model="createForm.s2Title"  label="Section Title"/>
                                            <Text v-model="createForm.s2Slogan"  label="Section Slogan"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body border-primary rounded-2">
                                            <h3 class="my-1">Section 2</h3>
                                            <Text v-model="createForm.s3Title"  label="Section Title"/>
                                            <Text v-model="createForm.s3Slogan"  label="Section Slogan"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body border-primary rounded-2">
                                            <h3 class="my-1">Promo Title</h3>
                                            <Text v-model="createForm.s4Title"  label="Section Title"/>
                                            <Text v-model="createForm.s4Slogan"  label="Section Slogan"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body border-primary rounded-2">
                                            <h3 class="my-1">FAQ Page</h3>
                                            <Text v-model="createForm.faqpagetitle"  label="Page Title"/>
                                            <Text v-model="createForm.faqpageslogan"  label="Page Slogan"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success" @click="updateSettings">Update Settings</button>
                        </div>
                    </div>
                </div>
                <div class="card" v-if="currentTab === 'homeMessage'">
                    <div class="card-header d-flex gap-2">
                        <h3 class="card-title m-0">Message</h3>
                    </div>
                    <div class="card-body">
                        <Text v-model="createForm.mstitle"  label="Message Title"/>
                        <Textarea v-model="createForm.msbody"  label="Message Body"/>

                        <button class="btn btn-success" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
                <div class="card" v-if="currentTab === 'contactUs'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Contact Us</h3>
                    </div>
                    <div class="card-body">
                        <Textarea v-model="createForm.contactUs" style="height:500px;" placeholder="Contact us page content...."/>
                        <button class="btn btn-success" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
                <div class="card" v-if="currentTab === 'headerPages'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Header Pages</h3>
                    </div>
                    <div class="card-body">
                        <v-select v-model="createForm.headerpages"
                                  label="title"
                                  multiple="true"
                                  :options="pages" :reduce="page => page.id"></v-select>

                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
                <div class="card" v-if="currentTab === 'footerPages'">
                    <div class="card-header d-flex gap-2 mb-1">
                        <h3 class="card-title m-0">Footer Pages</h3>
                    </div>
                    <div class="card-body">
                        <v-select v-model="createForm.footerpages"
                                  label="title"
                                  multiple="true"
                                  :options="pages" :reduce="page => page.id"></v-select>
                        <Textarea v-model="createForm.footerText" style="height:500px;" placeholder="Footer About Content...."/>
                        <button class="btn btn-success mt-5" @click="updateSettings">Update Settings</button>
                    </div>
                </div>
            </div>
        </div>



    </Layout>



</template>

<script setup>
import Layout from "@/Shared/Layout.vue";
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import Text from "@/Components/form/Text.vue";
import {onMounted, ref} from "vue";
import Textarea from "@/Components/form/Textarea.vue";

const props = defineProps({
    pages:Array,
    bSettings:Object,
})

const currentTab = ref('details')
const setActiveTab = (event) => currentTab.value = event

let createForm = useForm({
    s1Title  : props.bSettings.s1Title ?? '',
    s1Slogan : props.bSettings.s1Slogan ?? '',

    s2Title  : props.bSettings.s2Title ?? '',
    s2Slogan : props.bSettings.s2Slogan ?? '',

    s3Title  : props.bSettings.s3Title ?? '',
    s3Slogan : props.bSettings.s3Slogan ?? '',

    s4Title  : props.bSettings.s4Title ?? '',
    s4Slogan : props.bSettings.s4Slogan ?? '',

    mstitle  : props.bSettings.mstitle ?? '',
    msbody : props.bSettings.msbody ?? '',

    contactUs : props.bSettings.contactUs ?? '',

    faqpagetitle  : props.bSettings.faqpagetitle ?? '',
    faqpageslogan : props.bSettings.faqpageslogan ?? '',

    headerpages : props.bSettings.headerpages ?? '',
    footerpages : props.bSettings.footerpages ?? '',

    footerText   : props.bSettings.footerText ?? '',


    // logo_fabs   : props.bSettings.logo_fabs ?? '',
    // footer_logo   : props.bSettings.logo_fabs ?? '',
    // app_details : props.bSettings.details ?? '',
    // timezone    : props.bSettings.timezone?.tz ?? '',
    // country     : props.bSettings.country?.name ?? '',
    //
    // address     : props.bSettings.address ?? '',
    // email       : props.bSettings.email ?? '',
    // phone       : props.bSettings.phone ?? '',
    //
    // facebook_profile  : props.bSettings.facebook_profile ?? '',


})

const updateSettings = () =>{
    createForm.post('/settings-update', {
        onSuccess:()=>{
            alert("Setting Updated....")
        }
    })

}
// onMounted(()=> createForm.value = props.bSettings)

</script>
