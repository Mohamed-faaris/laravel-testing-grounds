import { Head, Link } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { BookOpen } from 'lucide-react';
import type { BreadcrumbItem } from '@/types';
import { index } from '@/routes/notes';

interface Note {
    id: number;
    title: string;
    content: string;
    published_at: string;
    user?: {
        name: string;
    };
}

interface NotesResponse {
    data: Note[];
    links: {
        next: string | null;
        prev: string | null;
    };
}

interface Props {
    notes: NotesResponse;
    auth?: {
        user: {
            role: string;
        } | null;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Public Notes',
        href: '#',
    },
];

export default function PublicNotes({ notes, auth }: Props) {
    return (
        <div className="min-h-screen bg-gradient-to-b from-background to-muted/20">
            <header className="border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
                <div className="container mx-auto flex h-16 items-center justify-between px-4">
                    <div className="flex items-center gap-2">
                        <BookOpen className="h-6 w-6 text-primary" />
                        <h1 className="text-xl font-bold">Public Notes</h1>
                    </div>
                    <div className="flex items-center gap-4">
                        {auth?.user ? (
                            <>
                                <Link
                                    href={index().url}
                                    className="text-sm text-muted-foreground hover:text-foreground"
                                >
                                    My Notes
                                </Link>
                                <Link href="/dashboard">
                                    <Button variant="outline" size="sm">
                                        Dashboard
                                    </Button>
                                </Link>
                            </>
                        ) : (
                            <>
                                <Link href="/login">
                                    <Button variant="outline" size="sm">
                                        Log in
                                    </Button>
                                </Link>
                                <Link href="/register">
                                    <Button size="sm">Get Started</Button>
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </header>

            <main className="container mx-auto px-4 py-8">
                <Head title="Public Notes" />

                <div className="mb-8 text-center">
                    <h2 className="mb-2 text-3xl font-bold tracking-tight">
                        Community Notes
                    </h2>
                    <p className="text-muted-foreground">
                        Discover notes shared by our community
                    </p>
                </div>

                {notes.data.length === 0 ? (
                    <Card className="mx-auto max-w-lg">
                        <CardContent className="py-12 text-center">
                            <BookOpen className="mx-auto mb-4 h-12 w-12 text-muted-foreground" />
                            <p className="text-lg font-medium">
                                No public notes yet
                            </p>
                            <p className="text-sm text-muted-foreground">
                                Be the first to share a note with the community!
                            </p>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {notes.data.map((note) => (
                            <Card
                                key={note.id}
                                className="flex flex-col transition-shadow hover:shadow-lg"
                            >
                                <CardHeader>
                                    <div className="flex items-start justify-between gap-2">
                                        <CardTitle className="line-clamp-2 flex-1">
                                            {note.title}
                                        </CardTitle>
                                        <Badge
                                            variant="secondary"
                                            className="shrink-0"
                                        >
                                            Published
                                        </Badge>
                                    </div>
                                    <CardDescription className="flex items-center gap-2">
                                        <span>
                                            By {note.user?.name || 'Unknown'}
                                        </span>
                                        <span>•</span>
                                        <span>
                                            {new Date(
                                                note.published_at,
                                            ).toLocaleDateString()}
                                        </span>
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="flex-1">
                                    <p className="line-clamp-4 text-sm text-muted-foreground">
                                        {note.content}
                                    </p>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}

                {(notes.links.next || notes.links.prev) && (
                    <div className="mt-8 flex justify-center gap-4">
                        {notes.links.prev && (
                            <Button variant="outline" asChild>
                                <Link href={notes.links.prev}>Previous</Link>
                            </Button>
                        )}
                        {notes.links.next && (
                            <Button variant="outline" asChild>
                                <Link href={notes.links.next}>Next</Link>
                            </Button>
                        )}
                    </div>
                )}
            </main>

            <footer className="mt-auto border-t py-6">
                <div className="container mx-auto px-4 text-center text-sm text-muted-foreground">
                    <p>Share your knowledge with the world</p>
                </div>
            </footer>
        </div>
    );
}
