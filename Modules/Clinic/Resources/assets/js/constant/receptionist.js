export const MODULE = 'receptionist'
export const EDIT_URL = (id) => {return {path: `${MODULE}/${id}/edit`, method: 'GET'}}
export const STORE_URL = () => {return {path: `${MODULE}`, method: 'POST'}}
export const UPDATE_URL = (id) => {return {path: `${MODULE}/${id}`, method: 'POST'}}
export const CHANGE_PASSWORD_URL = () => {return {path: `${MODULE}/change-password/`, method: 'POST'}}

export const COUNTRY_URL = () => {return {path: `country/index_list`, method: 'GET'}}
export const STATE_URL = (id) => {return {path: `state/index_list?country_id=${id}`, method: 'GET'}}
export const CITY_URL = (id) => {return {path: `city/index_list?state_id=${id}`, method: 'GET'}}
export const CLINIC_CENTER_LIST = ({id = ''}) => {return { path: `clinics/index_list?vendor_id=${id}`, method: 'GET' }}
export const VENDOR_LIST = ({system_service = ''}) => {return {path: `multivendors/index_list?system_service=${system_service}`, method: 'GET'}}
