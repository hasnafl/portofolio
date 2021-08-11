<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Klasifikasi Pelanggan</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Create New Customer Classification</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/klasifikasipelanggan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Kode Klasifikasi Pelanggan</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.classification_code">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Nama Klasifikasi Pelanggan</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.classification_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Akun Akrual Piutang</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="akrual" :reduce="akrual => akrual" label="accountname" @search="fetchAkrual" @input="fillAkrual"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Akun Piutang</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="piutang" :reduce="piutang => piutang" label="accountname" @search="fetchPiutang" @input="fillPiutang"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Akun Uang Muka</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <v-select :options="uangmuka" :reduce="uangmuka => uangmuka" label="accountname" @search="fetchUangmuka" @input="fillUangmuka"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Deskripsi</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.description">
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
                classification_code: "",
                classification_name: "",
                account_akrual_piutang: "",
                account_piutang: "",
                account_downpayment: "",
                description: "",
                prefix: "customers",
                company: "FNA",
                entity: null
            },
            akrual: [],
            piutang: [],
            uangmuka: []
        }
    },
    methods: {
        fillAkrual (value) {
            this.data.account_akrual_piutang = value.accountnumber
            // this.data.entityname = value.description
        },
        fillPiutang (value) {
            this.data.account_piutang = value.accountnumber
            // this.data.entityname = value.description
        },
        fillUangmuka (value) {
            this.data.account_downpayment = value.accountnumber
            // this.data.entityname = value.description
        },
        submit () {
            const findseq = { ...this.data, d: new Date().getTime() }

            this.$axios.$put('/api/v1/klasifikasipelanggan/getseq', findseq)
                .then((response) => {
                    if (this.data.classification_code == "") {
                        this.data.classification_code = response.data;
                    }
                    const data = { ...this.data, d: new Date().getTime() }

                    this.$axios.$put('/api/v1/klasifikasipelanggan', data)
                        .then((data) => {
                            this.$router.push({ path: `/klasifikasipelanggan/detail/${data.data.id}` })
                        })
                })
        },
        fetchAkrual (search = '') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/getaccount', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.akrual = response.data
                })
        },
        fetchPiutang (search = '1102') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/getaccount', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.piutang = response.data
                })
        },
        fetchUangmuka (search = '1105') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/getaccount', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.uangmuka = response.data
                })
        },
    },
    created () {
        this.fetchAkrual();
        this.fetchPiutang();
        this.fetchUangmuka();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
