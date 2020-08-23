import React, { useState } from 'react';
import { ServerEggVariable } from '@/api/server/types';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { usePermissions } from '@/plugins/usePermissions';
import InputSpinner from '@/components/elements/InputSpinner';
import Input from '@/components/elements/Input';
import tw from 'twin.macro';
import { debounce } from 'debounce';
import updateStartupVariable from '@/api/server/updateStartupVariable';
import useServer from '@/plugins/useServer';
import { ServerContext } from '@/state/server';
import useFlash from '@/plugins/useFlash';
import FlashMessageRender from '@/components/FlashMessageRender';

interface Props {
    variable: ServerEggVariable;
}

const VariableBox = ({ variable }: Props) => {
    const FLASH_KEY = `server:startup:${variable.envVariable}`;

    const server = useServer();
    const [ loading, setLoading ] = useState(false);
    const [ canEdit ] = usePermissions([ 'startup.update' ]);
    const { clearFlashes, clearAndAddHttpError } = useFlash();

    const setServer = ServerContext.useStoreActions(actions => actions.server.setServer);

    const setVariableValue = debounce((value: string) => {
        setLoading(true);
        clearFlashes(FLASH_KEY);

        updateStartupVariable(server.uuid, variable.envVariable, value)
            .then(response => setServer({
                ...server,
                variables: server.variables.map(v => v.envVariable === response.envVariable ? response : v),
            }))
            .catch(error => {
                console.error(error);
                clearAndAddHttpError({ error, key: FLASH_KEY });
            })
            .then(() => setLoading(false));
    }, 500);

    return (
        <TitledGreyBox title={variable.name}>
            <FlashMessageRender byKey={FLASH_KEY} css={tw`mb-4`}/>
            <InputSpinner visible={loading}>
                <Input
                    onKeyUp={e => setVariableValue(e.currentTarget.value)}
                    readOnly={!canEdit}
                    name={variable.envVariable}
                    defaultValue={variable.serverValue}
                    placeholder={variable.defaultValue}
                />
            </InputSpinner>
            <p css={tw`mt-1 text-xs text-neutral-400`}>
                {variable.description}
            </p>
        </TitledGreyBox>
    );
};

export default VariableBox;