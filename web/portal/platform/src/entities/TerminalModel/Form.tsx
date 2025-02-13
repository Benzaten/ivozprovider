import {
  EntityFormProps,
  FieldsetGroups,
} from '@irontec/ivoz-ui/entities/DefaultEntityBehavior';
import { Form as DefaultEntityForm } from '@irontec/ivoz-ui/entities/DefaultEntityBehavior/Form';

const Form = (props: EntityFormProps): JSX.Element => {
  const groups: Array<FieldsetGroups | false> = [
    {
      legend: '',
      fields: ['iden'],
    },
    {
      legend: '',
      fields: ['name'],
    },
    {
      legend: '',
      fields: ['description'],
    },
    {
      legend: '',
      fields: ['genericUrlPattern'],
    },
    {
      legend: '',
      fields: ['genericTemplate'],
    },
    {
      legend: '',
      fields: ['specificUrlPattern'],
    },
    {
      legend: '',
      fields: ['specificTemplate'],
    },
  ];

  return <DefaultEntityForm {...props} groups={groups} />;
};

export default Form;
