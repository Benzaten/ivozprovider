<?php

namespace Ivoz\Kam\Domain\Model\UsersCdr;

class UsersCdrDto extends UsersCdrDtoAbstract
{
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public static function getPropertyMap(string $context = '', string $role = null): array
    {
        if ($context === self::CONTEXT_COLLECTION) {
            $response = [
                'id' => 'id',
                'startTime' => 'startTime',
                'endTime' => 'endTime',
                'duration' => 'duration',
                'direction' => 'direction',
                'caller' => 'caller',
                'callee' => 'callee',
            ];

            if ($role !== 'ROLE_COMPANY_USER') {
                $response += [
                    'userId' => 'user',
                    'friendId' => 'friend'
                ];
            }
        } else {
            $response = parent::getPropertyMap(...func_get_args());
        }

        if ($role === 'ROLE_BRAND_ADMIN') {
            unset($response['brandId']);
        } elseif ($role === 'ROLE_COMPANY_ADMIN') {
            unset($response['companyId']);
        }

        return $response;
    }

    public function denormalize(array $data, string $context, string $role = ''): void
    {
        $contextProperties = self::getPropertyMap($context, $role);
        if ($role === 'ROLE_BRAND_ADMIN') {
            $contextProperties['brandId'] = 'brand';
        }

        $this->setByContext(
            $contextProperties,
            $data
        );
    }
}
