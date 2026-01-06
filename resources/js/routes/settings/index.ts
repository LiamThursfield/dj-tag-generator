import profile from './profile'
import userPassword from './user-password'
import appearance from './appearance'
import twoFactor from './two-factor'
import apiServices from './api-services'

const settings = {
    profile: Object.assign(profile, profile),
    userPassword: Object.assign(userPassword, userPassword),
    appearance: Object.assign(appearance, appearance),
    twoFactor: Object.assign(twoFactor, twoFactor),
    apiServices: Object.assign(apiServices, apiServices),
}

export default settings