import React from 'react'
import Dialog from "@material-ui/core/Dialog"
import PropTypes from "prop-types"

import Button from '@material-ui/core/Button';
import DialogActions from '@material-ui/core/DialogActions';
import DialogContent from '@material-ui/core/DialogContent';
import DialogTitle from '@material-ui/core/DialogTitle';
const AxiosConfirmModal = ({ title, show, onHide, onSuccess, url, method, data, ...props }) => {

    const onConfirm = () => {
        if (!url) return // TODO dovrebbe dare un errore ->aggiunto Proptype

        const source = axios.CancelToken.source()

        let axiosData = {
            url,
            method,
            cancelToken: source.token,
        }

        if (data) {
            axiosData.data = data
        }

        axios(axiosData)
            .then(response => {
                if (typeof onSuccess == 'function') {
                    onSuccess(response.data)
                } else onHide()

            })
            .catch(error => {
                if (axios.isCancel(error)) return;

                if (error.response) {
                    return window.alert(error.response.data.message)
                }

                return console.error(error)
            },
            )

        return () => source.CancelToken()
    }

    return <Dialog onClose={onHide} open={show}>

        {title && <DialogTitle>
            {title}
        </DialogTitle>}
        <DialogContent>
            {props.children}
        </DialogContent>
        <DialogActions>
            <Button variant="text" color="primary" onClick={onConfirm} >Conferma</Button>
            <Button variant="text" color="default" onClick={onHide} >Annulla</Button>
        </DialogActions>
    </Dialog>
}

AxiosConfirmModal.propTypes = {
    children: PropTypes.node,
    data: PropTypes.object,
    method: PropTypes.oneOf(['get', 'post', 'delete', 'put', 'patch']),
    onHide: PropTypes.func.isRequired,
    onSuccess: PropTypes.func,
    show: PropTypes.bool.isRequired,
    title: PropTypes.node,
    url: PropTypes.string.isRequired,
}

export default AxiosConfirmModal