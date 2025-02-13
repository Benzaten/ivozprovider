import useFkChoices from '@irontec/ivoz-ui/entities/data/useFkChoices';
import {
  EntityFormProps,
  FieldsetGroups,
  Form as DefaultEntityForm,
} from '@irontec/ivoz-ui/entities/DefaultEntityBehavior';

import { foreignKeyGetter } from './ForeignKeyGetter';

const Form = (props: EntityFormProps): JSX.Element => {
  const { edit } = props;
  const { entityService, row, match } = props;
  const fkChoices = useFkChoices({
    foreignKeyGetter,
    entityService,
    row,
    match,
  });

  const groups: Array<FieldsetGroups | false> = [
    {
      legend: '',
      fields: [edit && 'calendar'],
    },
    {
      legend: '',
      fields: ['name', 'locution'],
    },
    {
      legend: '',
      fields: ['eventDate', 'wholeDayEvent', 'timeIn', 'timeOut'],
    },
    {
      legend: '',
      fields: [
        'routeType',
        'numberCountry',
        'numberValue',
        'voicemail',
        'extension',
      ],
    },
  ];

  return <DefaultEntityForm {...props} fkChoices={fkChoices} groups={groups} />;
};

export default Form;
