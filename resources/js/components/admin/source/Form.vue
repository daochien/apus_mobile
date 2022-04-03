<template>
    <div class="row justify-content-center">
        <div class="col-md-12 text-right" style="margin-bottom: 10px;">
            <a href="/admin/sources" class="btn btn-responsive btn-warning">Hủy bỏ</a>
            <button @click.prevent="edit(form.id)" v-if="editModel" type="submit" class="btn btn-responsive btn-info">Cập nhật</button>
            <button @click.prevent="saveForm()" v-else class="btn btn-responsive btn-primary">Tạo mới</button>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="input">Tên source(*)</label>
                        <input v-model="form.name" :class="['form-control', { 'is-invalid': form.errors.has('name') }]">
                        <has-error :form="form" field="name"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="input">Mô tả</label>
                        <textarea rows="5" v-model="form.desc" :class="['form-control', { 'is-invalid': form.errors.has('desc') }]"></textarea>
                        <has-error :form="form" field="desc"></has-error>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="input">Source File(*)</label>
                        <div data-v-65473a7c="" class="d-flex justify-content-start">

                            <img
                                :src="imageSharePreview"
                                style="border: 1px solid"
                                width="100"
                                height="100"
                            />
                            <div data-v-65473a7c="" class="d-flex align-items-center ml-3">
                                <input
                                    type="file"
                                    @change="onSelectSourceFile($event)"
                                    :class="['form-control', {'is-invalid': form.errors.has('source') }]"
                                    id="icon"
                                    placeholder="Icon"
                                    accept=".zip"
                                    ref="fileupload"
                                />
                                <has-error :form="form" field="source"></has-error>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input">Ảnh đại diện</label>
                        <div data-v-65473a7c="" class="d-flex justify-content-start">

                            <img
                                :src="avatarPreview"
                                style="border: 1px solid"
                                width="100"
                                height="100"
                            />
                            <div data-v-65473a7c="" class="d-flex align-items-center ml-3">
                                <input
                                    type="file"
                                    @change="onSelectImageAvatar($event)"
                                    :class="['form-control', {'is-invalid': form.errors.has('avatar') }]"
                                    id="icon"
                                    placeholder="Icon"
                                    accept="image/*"
                                    ref="fileupload"
                                />
                                <has-error :form="form" field="avatar"></has-error>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input">Hiển thị</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.status" id="status1" value="1">
                                <label class="form-check-label" for="status1">Có</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.status" id="status2" value="0">
                                <label class="form-check-label" for="status2">Không</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

const img_link ="/images/no_image.jpg";

export default {
    props: ['editModel', 'source'],
    data () {
        return {
            form: new Form({
                name: '',
                avatar: '',
                source: '',
                desc: '',
                status: 1,
                configs: []
            }),
            formUpload: new Form({
               file: ''
            }),
            avatarPreview: img_link,
            imageSharePreview: img_link,
            configs: [
                {
                    'id': '',
                    'key': '',
                    'type': 'string',
                    'value': '',
                    'is_edit': true
                }
            ]
        }
    },
    created () {
        if (this.editModel) {
            this.loadEdit(this.source);
        }
    },
    methods: {
        addConfig () {
            this.configs.push({
                'id': '',
                'key': '',
                'type': 'string',
                'value': '',
                'is_edit': true
            });
        },
        removeConfig(index) {
            this.configs.splice(index, 1);
        },
        onSelectConfigFile (e, index) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;

                if (/\.(jpe?g|png|gif)$/i.test(files[0].name)) {
                    this.formUpload.file = files[0];
                    this.formUpload.submit('post','/admin/common/upload', {
                        transformRequest: [function (data, headers) {
                            return window.objectToFormData.serialize(data)
                        }],
                    })
                    .then(({data}) => {
                        if (data.status) {
                            this.configs[index].value = data.data.file_name;
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                        }
                    })
                    .catch((error) => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi hệ thống'
                        });
                    })
                } else {
                    this.configs[index].value = "";

                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },
        onSelectImageAvatar (e) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;
                if (/\.(jpe?g|png|gif)$/i.test(files[0].name)) {
                    this.form.avatar = files[0];
                    this.avatarPreview = URL.createObjectURL(files[0]);
                } else {
                    this.form.avatar = "";
                    if (!this.isEdit) {
                        this.form.avatarPreview = "";
                    }
                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },

        onSelectSourceFile (e) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;
                if (/\.(zip)$/i.test(files[0].name)) {
                    this.form.source = files[0];
                    //this.imageSharePreview = URL.createObjectURL(files[0]);
                } else {
                    this.form.source = "";
                    if (!this.isEdit) {
                        this.form.imageSharePreview = img_link;
                    }
                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },

        saveForm () {
            //this.form.configs = JSON.stringify(this.configs);
            this.form.submit('post', '/admin/sources', {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    if (data.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Tạo mới source mẫu thành công'
                        });
                        window.location.href = '/admin/sources';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        },
        loadEdit (source) {
            let that = this;
            this.form.id = source.id;
            this.form.name = source.name;
            this.form.desc = source.desc;
            this.form.status = source.status;
            this.avatarPreview = source.avatar ? source.avatar : img_link;
            if (source.configs.length > 0) {
                this.configs = [];
                source.configs.forEach((item) => {
                    let value = '';
                    if (item.type === 'radio' || item.type === 'checkbox') {
                        value = JSON.parse(item.value);
                    } else if (item.type === 'file') {
                        value = '';
                    } else {
                        value = item.value;
                    }
                    that.configs.push({
                        'id': item.id,
                        'key': item.key,
                        'type': item.type,
                        'value': value,
                        'is_edit': item.is_edit == 1? true: false
                    })
                })
            }

        },
        edit (id) {
            //this.form.configs = JSON.stringify(this.configs);
            this.form.submit('post', '/admin/sources/'+id, {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    let status = data.status ? 'success': 'error';

                    Toast.fire({
                        icon: status,
                        title: data.message
                    });

                    if (data.status)
                        window.location.href = '/admin/sources';

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        }
    }
}
</script>
