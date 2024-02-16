import { EnTextConfig } from "../Config/EnTextConfig"
import { TextConfig } from "../Config/TextConfig"

export const translateCivility = (frCivility) => {
    if(frCivility === TextConfig.CIVILITY_F) {
        return EnTextConfig.CIVILITY_F;
    } if(frCivility === TextConfig.CIVILITY_M) {
        return EnTextConfig.CIVILITY_M;
    }
    return frCivility;
}


export const translateStatus = (frStatus) => {
    switch(frStatus) {
        case TextConfig.STATUS_PENDING:
            return EnTextConfig.STATUS_PENDING;
        case TextConfig.STATUS_PAID:
            return EnTextConfig.STATUS_PAID;
        case TextConfig.STATUS_SENT:
            return EnTextConfig.STATUS_SENT;
        case TextConfig.STATUS_DELIVERED:
            return EnTextConfig.STATUS_DELIVERED;
        case TextConfig.STATUS_CANCELED:
            return EnTextConfig.STATUS_CANCELED;
        default:
            return frStatus;
    }
}