<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Customers</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Create New Customers</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/datapelanggan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Customer Branch</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="branch" :reduce="branch => branch" label="entityname" @search="fetchBranch" @input="fillBranch"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Customer Classification</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="classification" :reduce="classification => classification" label="classification_name" @search="fetchClassification" @input="fillClassification"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Customer Code</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.customer_code">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Customer Name</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.customer_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Customer Phone Number</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.customer_phone1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Main Address</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" v-model="data.customer_address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                            </div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@/components/Editor'

export default {
    components: {
        Editor
    },
    data () {
        return {
            data: {
                id: "",
                customer_code: "",
                customer_name: "",
                customer_branch: "",
                customer_address: "",
                customer_phone1: "",
                customer_classification: "",
                prefix: "customers",
                company: "FNA",
                entity: null
            },
            branch: [],
            classification: []
        }
    },
    methods: {
        fillBranch (value) {
            this.data.customer_branch = value.entitycode
            // this.data.entityname = value.description
        },
        fillClassification (value) {
            this.data.customer_classification = value.classification_code
            // this.data.entityname = value.description
        },
        // fillAddress (value) {
        //     console.log(value);
        //     // this.data.customer_address = value.
        //     // this.data.entityname = value.description
        // },
        submit () {
            const findseq = { ...this.data, d: new Date().getTime() }

            this.$axios.$put('/api/v1/klasifikasipelanggan/getseq', findseq)
                .then((response) => {
                    if (this.data.customer_code == "") {
                        this.data.customer_code = response.data;
                    }
                    const data = { ...this.data, d: new Date().getTime() }

                    this.$axios.$put('/api/v1/datapelanggan', data)
                        .then((data) => {
                            this.$router.push({ path: `/datapelanggan/detail/${data.data.id}` })
                        })
                })
        },
        fetchBranch (search = 'ENT02') {
            this.$axios.$get('/api/v1/datapelanggan/getbranch', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.branch = response.data
                })
        },
        fetchClassification (search = '') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.classification = response.data
                })
        },
        // fetchMainaddress (search = '') {
        //     this.$axios.$get('/api/v1/alamatpelanggan/getaddress', { params: { keyword: search, d: new Date().getTime() } })
        //         .then((response) => {
        //             this.mainaddress = response.data
        //         })
        // },
    },
    created () {
        this.fetchBranch();
        this.fetchClassification();
        // this.fetchMainaddress();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
