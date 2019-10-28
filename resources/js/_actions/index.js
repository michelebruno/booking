import { AUTHENTICATION_SUCCESSFUL } from "../_constants/action-types"

export function authenticate(payload) {
    return { type: AUTHENTICATION_SUCCESSFUL, payload }
};

