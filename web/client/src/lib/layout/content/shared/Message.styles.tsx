
import { styled } from '@mui/styles';
import { Theme } from '@mui/material';

import CloseIcon from '@mui/icons-material/Close';

export const StyledCloseIcon = styled(CloseIcon)(
    ({ theme }: { theme: Theme }) => {
        return {
            position: 'relative',
        }
    }
);

export const StyledSnackbarContentMessageContainer = styled(
    (props) => {
        const { children, className } = props;
        return (<span className={className}>{children}</span>);
    }
)(
    ({ theme }: { theme: Theme }) => {
        return {
            display: 'flex',
            alignItems: 'center',
        }
    }
);