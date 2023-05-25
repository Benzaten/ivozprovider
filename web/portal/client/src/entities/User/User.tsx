import defaultEntityBehavior from '@irontec/ivoz-ui/entities/DefaultEntityBehavior';
import EntityInterface from '@irontec/ivoz-ui/entities/EntityInterface';
import _ from '@irontec/ivoz-ui/services/translations/translate';
import PersonIcon from '@mui/icons-material/Person';

import StatusIcon from '../RetailAccount/Field/StatusIcon';
import { UserProperties, UserPropertyList } from './UserProperties';

const properties: UserProperties = {
  name: {
    label: _('Name'),
  },
  lastname: {
    label: _('Lastname'),
  },
  email: {
    label: _('Email'),
    helpText: _('Used as voicemail reception and user portal credential'),
  },
  pass: {
    label: _('Password'),
    format: 'password',
  },
  active: {
    label: _('Active'),
    enum: {
      '0': _('No'),
      '1': _('Yes'),
    },
    default: '0',
    visualToggle: {
      '0': {
        show: [],
        hide: ['pass'],
      },
      '1': {
        show: ['pass'],
        hide: [],
      },
    },
  },
  timezone: {
    label: _('Timezone'),
    default: 145,
  },
  transformationRuleSet: {
    label: _('Numeric transformation'),
    default: '__null__',
    null: _("Client's default"),
  },
  location: {
    label: _('Location'),
    null: _('Unassigned'),
    default: '__null__',
  },
  terminal: {
    label: _('Terminal'),
    null: _('Unassigned'),
    default: '__null__',
  },
  // @TODO 'statusIcon': _('Status'),
  extension: {
    label: _('Screen Extension'),
    null: _('Unassigned'),
    default: '__null__',
  },
  outgoingDdi: {
    label: _('Outgoing DDI'),
    null: _("Client's default"),
    default: '__null__',
  },
  outgoingDdiRule: {
    label: _('Outgoing DDI Rule'),
    null: _("Client's default"),
    default: '__null__',
    helpText: _(
      'Rules to manipulate outgoingDDI when user directly calls to external numbers.'
    ),
  },
  callAcl: {
    label: _('Call ACL'),
    null: _('Unassigned'),
  },
  doNotDisturb: {
    label: _('Do not disturb'),
    default: '0',
    enum: {
      '0': _('No'),
      '1': _('Yes'),
    },
  },
  isBoss: {
    label: _('Is boss'),
    enum: {
      '0': _('No'),
      '1': _('Yes'),
    },
    default: 0,
    visualToggle: {
      '0': {
        show: [],
        hide: ['bossAssistant', 'bossAssistantWhiteList'],
      },
      '1': {
        show: ['bossAssistant', 'bossAssistantWhiteList'],
        hide: [],
      },
    },
  },
  bossAssistant: {
    label: _('Assistant'),
  },
  bossAssistantWhiteList: {
    label: _('Boss Whitelist'),
    helpText: _('Origins matching this list will call directly to the user.'),
  },
  maxCalls: {
    label: _('Call waiting'),
    default: 0,
    minimum: 0,
    maximum: 100,
    helpText: _(
      'Limits received calls when already handling this number of calls. Set 0 for unlimited.'
    ),
  },
  pickupGroupIds: {
    label: _('Pick Up Groups'),
  },
  language: {
    label: _('Language'),
    default: '__null__',
    null: _("Client's default"),
  },
  externalIpCalls: {
    label: _('Calls from non-granted IPs'),
    default: 0,
    helpText: _(
      "Enable calling from non-granted IP addresses for this user. It limits the number of outgoing calls to avoid toll-fraud. 'None' value makes outgoing calls unlimited as long as company IP policy is fulfilled."
    ),
  },
  rejectCallMethod: {
    label: _('Call rejection method'),
    default: 'rfc',
    enum: {
      rfc: _('Depending on response code'),
      '486': _('Cancel call only in current device'),
      '600': _('Cancel call in all devices'),
    },
  },
  gsQRCode: {
    label: _('QR Code'),
    helpText: _(
      'Add QR Code to user portal to provision GS Wave mobile softphone'
    ),
  },
  multiContact: {
    label: _('Multi contact'),
    helpText: _(
      "Set to 'No' to call only to latest registered SIP device instead of making all registered devices ring."
    ),
    enum: {
      '0': _('No'),
      '1': _('Yes'),
    },
    visualToggle: {
      '0': {
        show: [],
        hide: ['rejectCallMethod'],
      },
      '1': {
        show: ['rejectCallMethod'],
        hide: [],
      },
    },
  },
  statusIcon: {
    label: _('Status'),
    component: StatusIcon,
  },
};

const columns = [
  'name',
  'lastname',
  'extension',
  'terminal',
  'outgoingDdi',
  'statusIcon',
];

const user: EntityInterface = {
  ...defaultEntityBehavior,
  icon: PersonIcon,
  iden: 'User',
  title: _('User', { count: 2 }),
  path: '/users',
  acl: {
    ...defaultEntityBehavior.acl,
    iden: 'Users',
  },
  toStr: (row: UserPropertyList<string>) => `${row.name} ${row.lastname}`,
  properties,
  columns,
  selectOptions: async () => {
    const module = await import('./SelectOptions');

    return module.default;
  },
  foreignKeyGetter: async () => {
    const module = await import('./ForeignKeyGetter');

    return module.foreignKeyGetter;
  },
  Form: async () => {
    const module = await import('./Form');

    return module.default;
  },
};

export default user;
