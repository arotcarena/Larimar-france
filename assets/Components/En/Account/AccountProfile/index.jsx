import React, { useEffect, useState } from 'react';
import { Loader } from '../../../../UI/Icon/Loader';
import { useOpenState } from '../../../../CustomHook/useOpenState';
import { EditButton } from '../../../../UI/Button/EditButton';
import { ProfileForm } from './ProfileForm';
import { apiFetch } from '../../../../functions/api';
import { LinkButton } from '../../../../UI/Button/LinkButton';
import '../../../../styles/Account/profile.css';
import { translateCivility } from '../../../../functions/translaters';

export const AccountProfile = ({user, setUser}) => {

    //edit
    const [isEdit, openEdit, closeEdit] = useOpenState(false);

    //user
    const [isLoading, setLoading] = useState(false);
    useEffect(() => {
        if(!user) {
            (async () => {
                setLoading(true);
                try {
                    const data = await apiFetch('/en/api/user/getCivilState');
                    setUser(data);
                } catch(e) {
                    //
                }
                setLoading(false);
            })();
        }
    }, []);



    if(isLoading) {
        return <Loader />
    } else if(!user) {
        return <div>Unable to load your details, please try again later</div>
    } else if(isEdit) {
        return (
            <div className="account-profile">
                <ProfileForm user={user} setUser={setUser} close={closeEdit}/>
            </div>
        )
    }
    return (
        <div className="account-profile">
            <div className="account-profile-block">
                {
                    user.civility && user.firstName && user.lastName && (
                        <p className="capitalize">{ translateCivility(user.civility) } { user.firstName } { user.lastName }</p>
                    )
                }
                <p>{user.email}</p>
                <EditButton onClick={openEdit} />
            </div>

            <div className="account-profile-controls">
                <LinkButton href="/en/change-my-password">Change my password</LinkButton>
                <LinkButton href="/en/delete-my-account" additionalClass="secondary">Delete my account</LinkButton>
            </div>
        </div>
    )
}