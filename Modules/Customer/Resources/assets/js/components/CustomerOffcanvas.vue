<template>
  <form @submit="formSubmit">
    <div class="offcanvas offcanvas-end offcanvas-w-40" tabindex="-1" id="form-offcanvas" aria-labelledby="form-offcanvasLabel">
      <FormHeader :currentId="currentId" :editTitle="editTitle" :createTitle="createTitle"></FormHeader>
      <div class="offcanvas-body">
        <div class="row">
          <div class="col-md-6 create-service-image">
            <label for="" class="form-label">{{ $t('customer.lbl_profile_image') }} </label>
            <ImageComponent :ImageViewer="image_url" v-model="profile_image" />
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="form-label">{{ $t('customer.lbl_first_name') }}<span class="text-danger">*</span></label>
              <InputField class="" :is-required="true" :label="$t('employee.lbl_first_name')" :placeholder="$t('clinic.lbl_first_name')" v-model="first_name" :error-message="errors['first_name']" :error-messages="errorMessages['first_name']"></InputField>
            </div>
            <div class="form-group">
              <label for="" class="form-label">{{ $t('customer.lbl_last_name') }}<span class="text-danger">*</span></label>
              <InputField class="" :is-required="true" :label="$t('employee.lbl_last_name')" :placeholder="$t('clinic.lbl_last_name')" v-model="last_name" :error-message="errors['last_name']" :error-messages="errorMessages['last_name']" autocomplete="off"> </InputField>
            </div>
            <div class="form-group">
              <label for="" class="form-label">{{ $t('customer.lbl_Email') }}</label>
              <InputField class="" :is-required="false" :label="$t('employee.lbl_Email')" :placeholder="$t('clinic.lbl_Email')" v-model="email" :error-message="errors['email']" :error-messages="errorMessages['email']"></InputField>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">{{ $t('clinic.lbl_gender') }} <span class="text-danger">*</span></label>
              <Multiselect id="gender" v-model="gender" :value="gender" v-bind="gender_data" class="form-group"> </Multiselect>
              <span v-if="errorMessages['gender']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['gender']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors.gender }}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="date_of_birth">{{ $t('customer.lbl_date_of_birth') }}<span class="text-danger">*</span></label>
              <flat-pickr :placeholder="$t('employee.date_of_birth')" id="date_of_birth" :is-required="true" class="form-control" v-model="date_of_birth" :value="date_of_birth" :config="config"></flat-pickr>
              <span class="text-danger">{{ errors.date_of_birth }}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">{{ $t('employee.lbl_phone_number') }}<span class="text-danger">*</span> </label>
              <vue-tel-input 
                ref="phoneInputRef"
                v-model="mobile" 
                mode="international" 
                autocomplete="new-password" 
                :inputOptions="{ placeholder: $t('employee.lbl_phone_number_placeholder') }" 
                :defaultCountry="'PK'"
                :preferredCountries="['PK', 'IN', 'US', 'GB']"
                @input="handleInput"
                @keydown="preventInvalidPhoneInput"
                @country-change="onPhoneCountryChange"
              />
              <span class="text-danger">{{ errors['mobile'] }}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">{{ $t('customer.lbl_cnic_number') }}</label>
              <InputField v-model="cnic_number" :is-required="false" :label="$t('customer.lbl_cnic_number')" :placeholder="$t('customer.lbl_cnic_number_placeholder')" :error-message="errors['cnic_number']" :error-messages="errorMessages['cnic_number']" maxlength="13" @input="cnic_number = cnic_number.slice(0, 13)"></InputField>
            </div>
          </div>
          <div class="col-md-6" v-if="currentId === 0">
            <div class="form-group">
              <label for="" class="form-label">{{ $t('profile.lbl_password') }}</label>
              <InputField type="password" class="" :is-required="false" :label="$t('employee.lbl_password')" :placeholder="$t('employee.lbl_password')" v-model="password" :error-message="errors['password']" :autocomplete="'new-password'" :error-messages="errorMessages['password']" id="customer-password"></InputField>
            </div>
          </div>
          <div class="col-md-6" v-if="currentId === 0">
            <div class="form-group">
              <label for="" class="form-label">{{ $t('profile.lbl_confirm_password') }}</label>
              <InputField type="password" class="" :is-required="false" :label="$t('employee.lbl_confirm_password')" :placeholder="$t('employee.lbl_confirm_password')" v-model="confirm_password" :error-message="errors['confirm_password']" :error-messages="errorMessages['confirm_password']" id="customer-confirm-password" :autocomplete="'new-password'"> </InputField>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="form-label">{{ $t('customer.lbl_status') }}</label>
              <div class="d-flex justify-content-between align-items-center form-control">
                <label class="form-label m-0" for="category-status">{{ $t('employee.lbl_status') }}</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" :value="1" name="status" id="category-status" type="checkbox" v-model="status" />
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 mt-4">
            <h5 class="mb-3">{{ $t('customer.other_details') }}</h5>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="address">{{ $t('clinic.lbl_address') }}</label>
              <div class="input-group">
                <input class="form-control" v-model="address" id="address" :placeholder="$t('clinic.lbl_address')" />
                <span class="input-group-text"></span>
              </div>
              <span v-if="errorMessages['address']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['address']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors.address }}</span>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">{{ $t('clinic.lbl_country') }}</label>
              <Multiselect id="country-list" v-model="country" :placeholder="$t('clinic.lbl_country')" :value="country" v-bind="singleSelectOption" :options="countries.options" @select="getState" class="form-group"> </Multiselect>
              <span v-if="errorMessages['country']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['country']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors['country'] }}</span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">{{ $t('clinic.lbl_state') }}</label>
              <Multiselect id="state-list" v-model="state" :placeholder="$t('clinic.lbl_state')" :value="state" v-bind="singleSelectOption" :options="states.options" @select="getCity" class="form-group"> </Multiselect>
              <span v-if="errorMessages['state']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['state']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors['state'] }}</span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">{{ $t('clinic.lbl_city') }}</label>
              <Multiselect id="city-list" v-model="city" :placeholder="$t('clinic.lbl_city')" :value="city" v-bind="singleSelectOption" :options="cities.options" class="form-group"></Multiselect>
              <span v-if="errorMessages['city']">
                <ul class="text-danger">
                  <li v-for="err in errorMessages['city']" :key="err">{{ err }}</li>
                </ul>
              </span>
              <span class="text-danger">{{ errors['city'] }}</span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="" class="form-label">{{ $t('clinic.lbl_postal_code') }}</label>
              <InputField class="" type="text" :is-required="true" :label="$t('clinic.lbl_postal_code')" :placeholder="$t('clinic.lbl_postal_code')" v-model="pincode" :error-message="errors['pincode']" :error-messages="errorMessages['pincode']"></InputField>
            </div>
          </div>
        </div>
      </div>

      <FormFooter :IS_SUBMITED="IS_SUBMITED"></FormFooter>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { EDIT_URL, STORE_URL, UPDATE_URL, COUNTRY_URL, STATE_URL, CITY_URL } from '../constant/customer'
import { useField, useForm } from 'vee-validate'

import { VueTelInput } from 'vue3-tel-input'

import { useModuleId, useRequest, useOnOffcanvasHide } from '@/helpers/hooks/useCrudOpration'
import * as yup from 'yup'

import { readFile } from '@/helpers/utilities'
import { useSelect } from '@/helpers/hooks/useSelect'

import FormHeader from '@/vue/components/form-elements/FormHeader.vue'
import FormFooter from '@/vue/components/form-elements/FormFooter.vue'
import InputField from '@/vue/components/form-elements/InputField.vue'
import FormElement from '@/helpers/custom-field/FormElement.vue'
import FlatPickr from 'vue-flatpickr-component'
import ImageComponent from '@/vue/components/form-elements/imageComponent.vue'

const { t } = useI18n()

// props
const props = defineProps({
  createTitle: { type: String, default: '' },
  editTitle: { type: String, default: '' },
  defaultImage: { type: String, default: 'https://dummyimage.com/600x300/cfcfcf/000000.png' },
  customefield: { type: Array, default: () => [] },
  selectedSessionServiceProviderId: { type: Number, default: null }
})

const config = ref({
  dateFormat: 'Y-m-d',
  static: true,
  maxDate: 'today'
})

// Select Options
const singleSelectOption = ref({
  closeOnSelect: true,
  searchable: true
})
const gender_data = ref({
  searchable: true,
  options: [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
    { label: 'Intersex', value: 'intersex' }
  ],
  closeOnSelect: true,
  createOption: false
})

const { getRequest, storeRequest, updateRequest } = useRequest()

// Edit Form Or Create Form
const currentId = useModuleId(() => {
  if (currentId.value > 0) {
    getRequest({ url: EDIT_URL, id: currentId.value }).then((res) => {
      if (res.status && res.data) {
        setFormData(res.data)

        getState(country.value)
        getCity(state.value)
      }
    })
  } else {
    setFormData(defaultData())
  }
})

onMounted(() => {
  // Don't pre-select any image for new patients
  image_url.value = null
  setFormData(defaultData())
  getCountry()
  
  // Set Pakistan as default after a short delay to ensure countries are loaded
  setTimeout(() => {
    if (countries.value.options && countries.value.options.length > 0) {
      const pakistan = countries.value.options.find(country => 
        country.label.toLowerCase().includes('pakistan') || 
        country.value.toString() === '166'
      )
      if (pakistan && !country.value) {
        resetForm({
          values: {
            ...defaultData(),
            country: pakistan.value
          }
        })
        getState(pakistan.value)
      }
    }
    
    // Force set Pakistan in phone input
    forcePakistanInPhoneInput()
  }, 500)
})

const countries = ref({ options: [], list: [] })

const getCountry = () => {
  useSelect({ url: COUNTRY_URL }, { value: 'id', label: 'name' }).then((data) => {
    countries.value = data
    // Set Pakistan as default country
    if (data.options && data.options.length > 0) {
      const pakistan = data.options.find(country => 
        country.label.toLowerCase().includes('pakistan') || 
        country.value.toString() === '166' // Pakistan's country code
      )
      if (pakistan) {
        // Set the country value directly in the form
        resetForm({
          values: {
            ...defaultData(),
            country: pakistan.value
          }
        })
        getState(pakistan.value)
        
        // Force update the phone input to use Pakistan
        setTimeout(() => {
          if (mobile.value && !mobile.value.startsWith('+92')) {
            // If mobile doesn't start with Pakistan code, update it
            const currentDigits = getDigitsOnly(mobile.value)
            if (currentDigits) {
              mobile.value = `+92 ${currentDigits}`
            }
          }
        }, 100)
      }
    }
  })
}

const states = ref({ options: [], list: [] })

const getState = (value) => {
  if (value) {
    useSelect({ url: STATE_URL, data: value }, { value: 'id', label: 'name' }).then((data) => {
      states.value = data
      // Reset state and city when country changes
      state.value = ''
      city.value = ''
      cities.value = { options: [], list: [] }
    })
  }
}

const cities = ref({ options: [], list: [] })

const getCity = (value) => {
  if (value) {
    useSelect({ url: CITY_URL, data: value }, { value: 'id', label: 'name' }).then((data) => {
      cities.value = data
      // Reset city when state changes
      city.value = ''
    })
  }
}

/*
 * Form Data & Validation & Handeling
 */
// Default FORM DATA
const defaultData = () => {
  errorMessages.value = {}
  return {
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    mobile: '',
    cnic_number: '',
    password: '',
    confirm_password: '',
    gender: 'male',
    profile_image: [],
    date_of_birth: '',
    address: '',
    city: '',
    country: '', // Will be set to Pakistan in getCountry()
    pincode: '',
    state: '',
    status: 1,
    custom_fields_data: {}
  }
}

const image_url = ref()
const phoneInputRef = ref(null)

//  Reset Form
const setFormData = (data) => {
  // Handle profile_image - ensure it's a string for ImageViewer prop
  if (data.profile_image && Array.isArray(data.profile_image) && data.profile_image.length > 0) {
    image_url.value = data.profile_image[0] // Take first image if it's an array
  } else if (data.profile_image && typeof data.profile_image === 'string') {
    image_url.value = data.profile_image
  } else {
    // For new patients or when no image is provided, don't show any image
    image_url.value = null
  }
  
  resetForm({
    values: {
      id: data.id,
      first_name: data.first_name,
      last_name: data.last_name,
      email: data.email,
      mobile: data.mobile,
      cnic_number: data.cnic_number || '',
      password: data.password,
      confirm_password: data.confirm_password,
      date_of_birth: data.date_of_birth,
      gender: data.gender,
      address: data.address,
      city: data.city,
      state: data.state,
      country: data.country,
      pincode: data.pincode,
      profile_image: data.profile_image,
      status: data.status ? true : false,
      gender: data.gender,

      custom_fields_data: data.custom_field_data
    }
  })
}

// Reload Datatable, SnackBar Message, Alert, Offcanvas Close
const reset_datatable_close_offcanvas = (res) => {
  IS_SUBMITED.value = false
  if (res.status) {
    window.successSnackbar(res.message)
    renderedDataTable.ajax.reload(null, false)
    bootstrap.Offcanvas.getInstance('#form-offcanvas').hide()
    setFormData(defaultData())
    // Reset image_url to null when form is closed
    image_url.value = null
  } else {
    window.errorSnackbar(res.message)
    errorMessages.value = res.all_message
  }
}

const numberRegex = /^\d+$/
const EMAIL_REGX = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/

// Country-wise validation functions
const getCountryValidation = () => {
  const selectedCountry = countries.value.options?.find(c => c.value === country.value)
  const countryName = selectedCountry?.label?.toLowerCase() || ''
  
  if (countryName.includes('pakistan')) {
    return {
      mobile: yup.string().test('valid-mobile', t('customer.pakistan_phone_ten_digits'), (val) => {
        const digits = getDigitsOnly(val)
        return digits.length === 10
      }),
      cnic: yup.string().test('cnic-format', t('customer.cnic_exactly_13_digits'), (value) => {
        if (!value) return true
        return /^\d{13}$/.test(value)
      }),
      pincode: yup.string().test('pincode-format', t('customer.pakistan_postal_five_digits'), (value) => {
        if (!value) return true
        return /^\d{5}$/.test(value)
      })
    }
  } else if (countryName.includes('india')) {
    return {
      mobile: yup.string().test('valid-mobile', t('customer.india_phone_ten_digits'), (val) => {
        const digits = getDigitsOnly(val)
        return digits.length === 10
      }),
      pincode: yup.string().test('pincode-format', t('customer.india_postal_six_digits'), (value) => {
        if (!value) return true
        return /^\d{6}$/.test(value)
      })
    }
  } else if (countryName.includes('united states') || countryName.includes('usa')) {
    return {
      mobile: yup.string().test('valid-mobile', t('customer.us_phone_ten_digits'), (val) => {
        const digits = getDigitsOnly(val)
        return digits.length === 10
      }),
      pincode: yup.string().test('pincode-format', t('customer.us_zip_five_digits'), (value) => {
        if (!value) return true
        return /^\d{5}$/.test(value)
      })
    }
  }
  
  // Default validation for other countries
  return {
    mobile: yup.string().test('valid-mobile', t('customer.phone_ten_digits'), (val) => {
      const digits = getDigitsOnly(val)
      return digits.length === 10
    }),
    pincode: yup.string().nullable()
  }
}

// Validations
const validationSchema = yup.object({
  first_name: yup
    .string()
    .required(t('customer.first_name_required_field'))
    .test('is-string', t('customer.only_strings_allowed'), (value) => {
      const specialCharsRegex = /[!@#$%^&*(),.?":{}|<>\-_;'\/+=\[\]\\]/
      return !specialCharsRegex.test(value) && !numberRegex.test(value)
    }),
  last_name: yup
    .string()
    .required(t('customer.last_name_required_field'))
    .test('is-string', t('customer.only_strings_allowed'), (value) => {
      const specialCharsRegex = /[!@#$%^&*(),.?":{}|<>\-_;'\/+=\[\]\\]/
      return !specialCharsRegex.test(value) && !numberRegex.test(value)
    }),
  email: yup
    .string()
    .nullable()
    .test('is-string', t('customer.only_alphabetic_start'), (value) => {
      if (!value) return true
      return !numberRegex.test(value)
    })
    .test('email-format', t('customer.must_be_valid_email'), (value) => {
      if (!value) return true
      return EMAIL_REGX.test(value)
    }),
  mobile: yup.string().test('valid-mobile', t('customer.phone_number_ten_digits'), (val) => {
    const digits = getDigitsOnly(val)
    return digits.length === 10
  }),
  cnic_number: yup
    .string()
    .nullable()
    .test('cnic-format', t('customer.cnic_exactly_13_digits'), (value) => {
      if (!value) return true
      return /^\d{13}$/.test(value)
    }),
  password: yup
    .string()
    .nullable()
    .test('password', t('customer.password_required'), function (value) {
      if (currentId === 0 && !value) {
        return true
      }
      return true
    })
    .test('password-length', t('customer.password_min_length'), function (value) {
      if (!value) return true
      return value.length >= 8
    }),
  confirm_password: yup
    .string()
    .nullable()
    .test('confirm_password', t('customer.confirm_password_required'), function (value) {
      if (currentId === 0 && !value) {
        return true
      }
      return true
    })
    .oneOf([yup.ref('password')], t('customer.passwords_must_match')),
  date_of_birth: yup.string().required(t('customer.date_of_birth_required_field')).typeError(t('customer.invalid_date_format')),
  pincode: yup.string().nullable()
})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema
})

const { value: id } = useField('first_name')
const { value: first_name } = useField('first_name')
const { value: last_name } = useField('last_name')
const { value: email } = useField('email')
const { value: gender } = useField('gender')
const { value: mobile } = useField('mobile')
const { value: cnic_number } = useField('cnic_number')
const { value: profile_image } = useField('profile_image')
const { value: custom_fields_data } = useField('custom_fields_data')
const { value: password } = useField('password')
const { value: confirm_password } = useField('confirm_password')
const { value: date_of_birth } = useField('date_of_birth')
const { value: address } = useField('address')
const { value: city } = useField('city')
const { value: state } = useField('state')
const { value: country } = useField('country')
const { value: pincode } = useField('pincode')
const { value: status } = useField('status')

const errorMessages = ref({})

const getDigitsOnly = (val) => {
  return (val || '').replace(/^\+\d+\s*/, '').replace(/\D/g, '')
}

// Watch for country changes to update validation
watch(country, (newCountry) => {
  if (newCountry) {
    getState(newCountry)
    // Update validation schema based on country
    updateValidationSchema()
  }
})

// Watch for mobile input changes - only restrict when adding, not when editing
watch(mobile, (newVal, oldVal) => {
  if (newVal && oldVal && newVal.length > oldVal.length) {
    // Only check when adding characters, not when removing
    const digits = getDigitsOnly(newVal)
    if (digits.length > 10) {
      const countryCode = (newVal.match(/^\+\d+/) || [])[0] || ''
      mobile.value = countryCode ? `${countryCode} ${digits.slice(0, 10)}` : digits.slice(0, 10)
    }
  }
})

// Update validation schema based on selected country
const updateValidationSchema = () => {
  const countryValidation = getCountryValidation()
  
  // Update mobile validation
  if (countryValidation.mobile) {
    validationSchema.fields.mobile = countryValidation.mobile
  }
  
  // Update pincode validation
  if (countryValidation.pincode) {
    validationSchema.fields.pincode = countryValidation.pincode
  }
}

// Phone number input handler
const handleInput = (phone, phoneObject) => {
  if (phoneObject?.formatted) {
    // Allow the input but store the formatted version
    mobile.value = phoneObject.formatted
  }
}

// Handle phone country change
const onPhoneCountryChange = (countryData) => {
  // Sync the phone country with our form country if needed
  if (countryData && countryData.iso2) {
    // You can add logic here to sync with form country if needed
    console.log('Phone country changed to:', countryData.iso2)
  }
}

// Force set Pakistan as default in phone input
const forcePakistanInPhoneInput = () => {
  setTimeout(() => {
    if (phoneInputRef.value) {
      // Try to access the internal methods of vue-tel-input
      try {
        // Set Pakistan as the default country
        if (phoneInputRef.value.setCountry) {
          phoneInputRef.value.setCountry('PK')
        }
        
        // If mobile has a value but doesn't start with +92, update it
        if (mobile.value && !mobile.value.startsWith('+92')) {
          const currentDigits = getDigitsOnly(mobile.value)
          if (currentDigits) {
            mobile.value = `+92 ${currentDigits}`
          }
        }
      } catch (error) {
        console.log('Could not set country programmatically:', error)
      }
    }
  }, 100)
}

// Prevent additional phone number input when limit is reached
const preventInvalidPhoneInput = (event) => {
  // Allow backspace, delete, arrow keys, and other navigation keys
  if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab'].includes(event.key)) {
    return true
  }
  
  // Only allow numeric input
  if (!/[0-9]/.test(event.key)) {
    event.preventDefault()
    return false
  }

  const currentPhone = mobile.value || ''
  const phoneWithoutCountry = currentPhone.replace(/^\+\d+[\s-]*/, '')
  const digitsOnly = phoneWithoutCountry.replace(/\D/g, '')

  // Allow input if less than 10 digits
  if (digitsOnly.length < 10) {
    return true
  }
  
  // Prevent additional numeric input if already at 10 digits
  if (digitsOnly.length >= 10) {
    event.preventDefault()
    return false
  }
}

const IS_SUBMITED = ref(false)

// Form Submit
const formSubmit = handleSubmit((values) => {
  IS_SUBMITED.value = true
  values.custom_fields_data = JSON.stringify(values.custom_fields_data)

  // Handle empty email and password for new patients
  if (currentId.value === 0) {
    if (!values.email || values.email.trim() === '') {
      values.email = ''
    }
    if (!values.password || values.password.trim() === '') {
      values.password = ''
    }
    if (!values.confirm_password || values.confirm_password.trim() === '') {
      values.confirm_password = ''
    }
  }

  if (currentId.value > 0) {
    updateRequest({ url: UPDATE_URL, id: currentId.value, body: values, type: 'file' }).then((res) => reset_datatable_close_offcanvas(res))
  } else {
    storeRequest({ url: STORE_URL, body: values, type: 'file' }).then((res) => reset_datatable_close_offcanvas(res))
  }
})

useOnOffcanvasHide('form-offcanvas', () => {
  setFormData(defaultData())
  // Reset image_url to null when offcanvas is hidden
  image_url.value = null
})
</script>
