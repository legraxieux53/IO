<?php

namespace Lnl\IO\Files;

/**
* 
*/
class File
{
	////////////////
	// constantes //
	////////////////
	const FILE_DELETE_ERROR = 500;
	const FILE_DELETE_SUCCESS = 201;
	const CREATE_PATH_ERROR = 500;
	const CREATE_PATH_SUCCESS = 201;
	const FILE_CREATE_SUCCESS = 201;
	const FILE_CREATE_ERROR = 500;
	const FILE_WRITE_SUCCESS = 201;
	const FILE_WRITE_ERROR = 500;

	///////////////
	// variables //
	///////////////
	private $name;
	private $path;
	private $extension;

	/**
	 * @param  string
	 * @return [type]
	 */
	public function init ($full_name)
	{
		$this->findName($full_name);
		$this->findPath($full_name);
		$this->findExtension($full_name);
	}

	/**
	 * cree le ficher
	 * @param  string $content [description]
	 * @return [type]          [description]
	 */
	public function create()
	{
	    if(!$this->isFile($this->getName())){
            $this->createPath($this->path);
            $file = fopen($this->getName(), "w");
            fclose($file);
        }
	}

	/**
	 * Ecrit dans un fichier En ecrasant son contenu
	 * @param  string $content [description]
	 * @return [type]          [description]
	 */
	public function writeOver($content)
	{
		$this->createPath($this->path);
		file_put_contents($this->getName(), $content);
	}

	/**
	 * Ecrire dans le fichier
	 * @param  string $content [description]
	 * @return [type]          [description]
	 */
	public function write ($content)
	{
		$this->createPath($this->path);
		file_put_contents($this->getName(), $content, FILE_APPEND);
	}

    /**
     * Converti une chaine encodée en base64 en fichier
     * @param string $content
     */
	public function writeBase64 ($content)
    {
        $parts = explode(',', $content);
        if(count($parts) > 1) {
            $base64_encoded = $parts[1];
            $this->writeOver(base64_decode($base64_encoded));
            return self::FILE_CREATE_SUCCESS;
        } else {
            return self::FILE_CREATE_ERROR;
        }

    }

	/**
	 * Lire le fichier
	 * @return [type] [description]
	 */
	public function read ()
	{
		return file_get_contents($this->getName());
	}

	
	/**
	 * Supprime le fichier. Si la suppression a bien été éffectuée, il retourne
	 * la constante FILE_DELETE_SUCCESS, sinon, elle retourne FILE_DELETE_ERROR.
	 * @return integer
	 */
	public function delete()
	{
		try 
		{
			unlink($this->getName());
			return self::FILE_DELETE_SUCCESS;
		} 
		catch (Exception $e) 
		{
			return self::FILE_DELETE_ERROR;
		}
	}

	public function deleteLine ($line)
	{
		$contents = $this->read();
		$contents = str_replace($line, '', $contents);
		$this->writeOver($contents);
	}

	/**
	 * genere un nom de fichier
	 * @return [type] [description]
	 */
	public function generateName ()
	{
	    $chars = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9'];
	    $cod = self::DEFAULT_AGENT;
	    for ($i=0; $i < 5; $i++) { 
	        $cod .= $chars[mt_rand(0, count($chars) - 1)];
	    }
	    $cod .= '_';
	    $cod .= time();
	    return $cod;
	}

	/**
	 * recupère le nom du fichier
	 * @param  string
	 * @return null
	 */
	private function findName ($full_name)
	{
		$partsA = explode("/", $full_name);
		$partsB = explode(".", $partsA[count($partsA) - 1]); 
		$name = null;
		for ($i=0; $i < count($partsB) - 1; $i++) 
		{ 
			if ($i == 0) 
			{
				$name .= $partsB[$i];			
			} 
			else 
			{
				$name .= ".";			
				$name .= $partsB[$i];			
			}
		}
		$this->name = $name;
	}

	/**
	 * @param  string
	 * @return [type]
	 */
	private function findPath ($full_name)
	{
		$partsA = explode("/", $full_name);
		$path = null;
		for ($i=0; $i < count($partsA) - 1; $i++) 
		{ 
			if($i == 0) 
			{
				$path .= $partsA[$i];
			} 
			else 
			{
				$path .= "/";
				$path .= $partsA[$i];
			}
		}
		$this->path = $path;
	}

	/**
	 * @param  string
	 * @return [type]
	 */
	private function findExtension ($full_name)
	{
		$partsA = explode(".", $full_name);
		$this->extension = $partsA[count($partsA) - 1];
	}

	/**
	 * @param  string
	 * @return boolean
	 */
	private function isPath ($str)
	{
		return is_dir($str);
	}

	/**
	 * @param  string
	 * @return boolean
	 */
	private function isFile ($str)
	{
		return is_file($str);
	}

	/**
	 * @param  string
	 * @return [type]
	 */
	private function createPath ($str)
	{
		try 
		{
			$partsA = explode("/", $str);
			$dir_recursive = null;
			for ($i=0; $i < count($partsA); $i++) 
			{ 
				if ($i == 0) 
				{
					$dir_recursive .= $partsA[$i];
				} 
				else  
				{
					$dir_recursive .= "/";
					$dir_recursive .= $partsA[$i];
				}

				if (!$this->isPath($dir_recursive)) 
				{
					mkdir($dir_recursive);
				}
			}
			return self::CREATE_PATH_SUCCESS;
		} 
		catch (Exception $e) 
		{
			return self::CREATE_PATH_ERROR;
		}
	}

	/**
	 * retourne le chemin du fichier
	 * @return [type] [description]
	 */
	private function getName ()
	{
		return $this->path . "/" . $this->name . "." . $this->extension;
	}

	/**
	 * retourne le chemin absolu du fichier
	 * @return [type] [description]
	 */
	public function gerUrlName ()
	{
		return asset($this->getName());
	}
}