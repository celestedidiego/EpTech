<?php

// Includi il Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Funzione per ottenere tutte le classi nel namespace
function get_classes_in_directory($dir) {
    $classes = [];
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $file) {
        if ($file->getExtension() == 'php') {
            $className = get_class_from_file($file);
            if ($className) {
                $classes[] = $className;
            }
        }
    }
    return $classes;
}

// Funzione per ottenere il nome della classe da un file PHP
function get_class_from_file($file) {
    $contents = file_get_contents($file->getRealPath());
    if (preg_match('/namespace\s+([^;]+);/', $contents, $namespaceMatch)) {
        $namespace = $namespaceMatch[1];
    } else {
        $namespace = '';
    }
    
    if (preg_match('/class\s+(\w+)/', $contents, $classMatch)) {
        return $namespace ? $namespace . '\\' . $classMatch[1] : $classMatch[1];
    }
    
    return null;
}

// Funzione per generare il codice UML
function generate_uml($classes) {
    $uml = "@startuml\n";
    
    foreach ($classes as $class) {
        $uml .= "class $class {\n";
        $reflection = new ReflectionClass($class);
        foreach ($reflection->getMethods() as $method) {
            $visibility = $method->isPublic() ? '+' : ($method->isProtected() ? '#' : '-');
            $uml .= "  $visibility" . $method->getName() . "()\n";
        }
        $uml .= "}\n";
    }
    
    // Aggiungi le relazioni (questo è un esempio di base)
    foreach ($classes as $classA) {
        foreach ($classes as $classB) {
            if ($classA != $classB) {
                $uml .= "$classA <|-- $classB\n";  // Relazione di ereditarietà esempio
            }
        }
    }
    
    $uml .= "@enduml\n";
    return $uml;
}

// Imposta la directory del tuo progetto (dove si trovano le classi PHP)
$projectDir = __DIR__ . '/src';  // Cambia con la tua directory delle classi PHP

// Ottieni tutte le classi
$classes = get_classes_in_directory($projectDir);

// Genera il codice UML
$umlCode = generate_uml($classes);

// Salva il codice UML in un file .puml
file_put_contents('classes_diagram.puml', $umlCode);

echo "Diagramma UML generato con successo in 'classes_diagram.puml'.\n";

?>